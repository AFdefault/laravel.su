<?php

namespace App;

use App\Models\Document;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Yaml\Yaml;

class Docs
{
    /**
     * Default version of Laravel documentation
     */
    public const DEFAULT_VERSION = '10.x';

    /**
     * Array of supported versions
     */
    public const SUPPORT_VERSIONS = [
        '10.x',
        '8.x',
        '5.4',
        '4.2',
    ];

    /**
     * @var string The version of the documentation.
     */
    public $version;

    /**
     * @var string The path to the Markdown file.
     */
    protected $path;

    /**
     * @var array The array of variables extracted from the Markdown file's front matter.
     */
    protected array $variables = [];

    /**
     * @var string The content of the Markdown file.
     */
    protected $content;

    /**
     * @var string The file name.
     */
    public string $file;

    /**
     * @var Document
     */
    protected $model;

    /**
     * Create a new Docs instance.
     *
     * @param string $version The version of the Laravel documentation.
     * @param string $file    The file name.
     */
    public function __construct(string $version, string $file)
    {
        $this->file = $file.'.md';
        $this->version = $version;
        $this->path = "/$version/$this->file";

        $this->content();
    }

    /**
     * @return array
     */
    public function variables(): array
    {
        return $this->variables;
    }

    /**
     * @return string|null
     */
    public function content(): ?string
    {
        if ($this->content !== null) {
            return $this->content;
        }

        $raw = Cache::remember('doc-file-'.$this->path, now()->addMinutes(30), fn () => Storage::disk('docs')->get($this->path));

        // Abort the request if the page doesn't exist
        abort_if(
            $raw === null && Document::where('file', $this->file)->exists(),
            redirect(status: 300)->route('docs', ['version' => $this->version, 'page' => 'installation'])
        );

        $variables = Str::of($raw)
            ->after('---')
            ->before('---');

        try {
            $this->variables = Yaml::parse($variables);
        } catch (\Throwable) {

        }

        $this->content = Str::of($raw)
            ->replace('{{version}}', $this->version)
            ->after('---')
            ->after('---')
            ->markdown();

        return $this->content;
    }

    /**
     * Get the rendered view of a documentation page.
     *
     * @param string $view The view name.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return \Illuminate\Contracts\View\View The rendered view of the documentation page.
     */
    public function view(string $view)
    {
        $all = collect()->merge($this->variables)->merge([
            'docs'    => $this,
            'content' => $this->content(),
            'edit'    => $this->getEditUrl(),
        ]);

        return view($view, $all);
    }

    /**
     * Get the menu array for the documentation index page.
     *
     * @return array The menu array.
     */
    public function getMenu(): array
    {
        return Cache::remember('doc-navigation-'.$this->version, now()->addHours(2), function () {
            $page = Storage::disk('docs')->get($this->version.'/documentation.md');

            $html = Str::of($page)
                ->after('---')
                ->after('---')
                ->replace('{{version}}', $this->version)
                ->markdown()
                ->toString();

            return $this->docsToArray($html);
        });
    }

    /**
     * Get the title of the documentation page.
     *
     * @return string|null The title of the documentation page.
     */
    public function title(): ?string
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($this->content());

        $title = $crawler->filterXPath('//h1');

        return count($title) ? $title->text() : null;
    }

    /**
     * Convert the HTML string to an array.
     *
     * @param string $html The HTML string.
     *
     * @return array The converted array.
     */
    public function docsToArray(string $html): array
    {
        $crawler = new Crawler();
        $crawler->addContent($html);

        $crawler = new Crawler($html);

        $menu = [];

        $crawler->filter('ul > li')->each(function (Crawler $node) use (&$menu) {
            $subList = $node->filter('ul > li')->each(fn (Crawler $subNode) => [
                'title' => $subNode->filter('a')->text(),
                'href'  => url($subNode->filter('a')->attr('href')),
            ]);

            if (empty($subList)) {
                return null;
            }

            $menu[] = [
                'title' => $node->filter('h2')->text(),
                'list'  => $subList,
            ];
        });

        return $menu;
    }

    /**
     * Get all the versions of the documentation.
     *
     * @param string $version The version of the Laravel documentation.
     *
     * @return \Illuminate\Support\Collection A collection of Docs instances.
     */
    public static function every(string $version): Collection
    {
        $files = Storage::disk('docs')->allFiles($version);

        return collect($files)
            ->filter(fn (string $path) => Str::of($path)->endsWith('.md'))
            ->filter(fn (string $path) => ! Str::of($path)->endsWith(['readme.md', 'license.md']))
            ->map(fn (string $path) => Str::of($path)->after($version.'/')->before('.md'))
            ->map(fn (string $path) => new static($version, $path));
    }

    /**
     * Fetch the number of commits behind the current commit.
     *
     * @return int The number of commits behind.
     */
    public function fetchBehind(): int
    {
        throw_unless(isset($this->variables['git']), new Exception("The document {$this->path} is missing a Git hash"));

        $response = $this->fetchGitHubDiff();

        return $response
            ->takeUntil(fn ($commit) => $commit['sha'] === $this->variables['git'])
            ->count();
    }

    public function fetchLastCommit(): string
    {
        throw_unless(isset($this->variables['git']), new Exception("The document {$this->path} is missing a Git hash"));

        $response = $this->fetchGitHubDiff();

        return $response->pluck('sha')->first();
    }

    /**
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchGitHubDiff(?string $key = null): Collection
    {
        $hash = sha1($this->content());

        return Cache::remember("docs-diff-$this->version-$this->file-$hash",
            now()->addHours(2),
            fn () => Http::withBasicAuth('token', config('services.github.token'))
                ->get("https://api.github.com/repos/laravel/docs/commits?sha={$this->version}&path={$this->file}")
                ->collect($key)
        );
    }

    /**
     * Get the URL to edit the page on GitHub.
     *
     * @return string The URL to edit the page on GitHub.
     */
    public function getEditUrl(): string
    {
        return "https://github.com/laravelRus/docs/edit/$this->path";
    }

    /**
     * Get the URL to the original Laravel documentation page.
     *
     * @return string The URL to the original Laravel documentation page.
     */
    public function getOriginalUrl(): string
    {
        $urlPart = Str::of($this->path)->remove('.md');

        return "https://laravel.com/docs$urlPart";
    }

    /**
     * @param string $version
     * @param string $hash
     *
     * @return string
     */
    public static function compareLink(string $version, string $hash): string
    {
        $compactHash = Str::of($hash)->limit(7, '')->toString();

        return "https://github.com/laravel/docs/compare/$compactHash..$version";
    }

    /**
     * Get the Document model for the documentation page.
     *
     * @return \App\Models\Document The Document model.
     */
    public function getModel(): Document
    {
        if ($this->model === null) {
            $this->model = Document::firstOrNew([
                'version' => $this->version,
                'file'    => $this->file,
            ]);
        }

        return $this->model;
    }

    /**
     * @return int|null
     */
    public function behind(): ?int
    {
        return $this->getModel()->behind;
    }

    /**
     * @return string
     */
    public function isOlderVersion()
    {
        return $this->version !== static::DEFAULT_VERSION;
    }

    /**
     * Update the Document model with the latest information.
     *
     * @return void
     */
    public function update()
    {
        $this->content();

        $this->getModel()->fill([
            'behind'         => $this->fetchBehind(),
            'last_commit'    => $this->fetchLastCommit(),
            'current_commit' => $this->variables['git'],
        ])->save();
    }
}
