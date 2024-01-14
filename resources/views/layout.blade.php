@extends('html')

@section('body')
    {{--
    <div class="bg-primary text-bg-primary bg-gradient text-center py-2 d-none d-xxl-block">
        Скоро увидимся! Билеты уже доступны на <a href="{{ route('meets') }}" class="text-white">сайте</a>.
    </div>
    --}}


    <div class="container mt-md-4 mb-3">
        <div class="my-2 my-lg-4">
            <header class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="col-md-auto d-lg-none me-2 me-sm-3">
                    <a href="{{ route('nav') }}" class="link-body-emphasis text-decoration-none d-flex align-items-center">
                        {{--
                        <x-icon path="bs.list" width="2em" height="2em" class="me-1" />
--}}

                        <img src="https://laravel.com/img/logomark.min.svg" height="32" class="me-2">

                        {{ View::getSection('type') ? View::getSection('type') : View::getSection('title') }}
                    </a>
                </div>
                <div class="col-md-auto me-auto me-lg-2">
                    <a href="{{ route('home') }}">
                        <img src="https://laravel.su/images/logo.png" height="40" class="d-lg-inline d-none">
                    </a>
                </div>

                <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 d-none d-lg-flex">
                    {{--
                        <li><a href="{{ route('home') }}" class="nav-link px-3 link-body-emphasis">Главная</a></li>
                    --}}
                    <li><a href="{{ route('feature') }}" class="nav-link px-3 link-body-emphasis">Возможности</a></li>
                    <li><a href="{{ route('feed') }}" class="nav-link px-3 link-body-emphasis">Трибуна</a></li>
                    <li><a href="{{ route('packages') }}" class="nav-link px-3 link-body-emphasis">Пакеты</a></li>
                    <li><a href="{{ route('jobs') }}" class="nav-link px-3 link-body-emphasis">Работа</a></li>
                    <li><a href="{{ route('courses') }}" class="nav-link px-3 link-body-emphasis position-relative">Курсы
                            <span class="badge bg-primary position-absolute top-0 start-100 translate-middle mt-2">Новое</span></a>
                    </li>
                </ul>

                <div class="nav text-end">
                    <a href="{{ route('docs') }}" class="nav-link link-body-emphasis d-none d-md-inline-flex">Документация</a>
                    <a href="{{ route('nav.docs') }}" class="nav-link link-body-emphasis d-md-none">
                        <x-icon path="bs.book-half" height="1.5em" width="1.5em" />
                    </a>

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Войти</a>
                    @else
                        <a href="{{ route('profile', auth()->user()) }}"
                           class="avatar avatar-sm position-relative">
                            <img src="{{ auth()->user()->avatar }}" class="avatar-img rounded-circle border border-tertiary-subtle">

                            @if(auth()->user()->unreadNotifications()->exists())
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-light-subtle rounded-circle">
                                    <span class="visually-hidden">Есть не прочитанные уведомления</span>
                                </span>
                            @endif
                        </a>
                    @endif
                </div>
            </header>
        </div>
    </div>

    @yield('content')

    <div class="mt-5 pe-none d-none d-md-block">
        <img src="/img/sun.svg" class="w-100 object-fit-cover" style="object-position: top center;">
    </div>
    <div class="bg-dark-subtle text-white d-none d-md-block" data-bs-theme="dark">
        <div class="container py-5">
            <footer class="row py-md-5 g-4 justify-content-between navbar-dark">
                <div class="col-12 col-md-4">
                    {{--
                    <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                        <img src="https://laravel.su/images/logo.png" style="
    object-fit: contain;
    height: 45px;">
                    </a>
--}}
                    <ul class="nav justify-content-start align-items-center list-unstyled d-flex mb-4">
                        <li class="">
                            <a href="#" class="link-body-emphasis">
                                <x-icon path="bs.twitter" width="24" height="24"/>
                            </a>
                        </li>
                        <li class="ms-3">
                            <a class="link-body-emphasis" href="#">
                                <x-icon path="bs.youtube" width="24" height="24"/>
                            </a>
                        </li>
                        <li class="ms-3">
                            <a class="link-body-emphasis" href="#">
                                <x-icon path="bs.telegram" width="24" height="24"/>
                            </a>
                        </li>
                        <li class="ms-3">
                            <a class="link-body-emphasis" href="{{ asset('https://github.com/laravelRus') }}"
                               target="_blank">
                                <x-icon path="bs.github" width="24" height="24"/>
                            </a>
                        </li>
                        <li class="ms-3">
                            <a class="link-body-emphasis" href="{{ asset('/rss/feed') }}"
                               target="_blank">
                                <x-icon path="bs.rss-fill" width="24" height="24"/>
                            </a>
                        </li>
                    </ul>

                    <p class="small text-muted mb-2">
                        Веб-сайт является неофициальным ресурсом, посвященным Laravel. Мы объединяем разработчиков и
                        энтузиастов, желающих обмениваться знаниями и опытом. Мы не имеем официального статуса от
                        <a href="https://laravel.com" class="link-body-emphasis">Laravel</a> или Taylor Otwell.
                    </p>
                    <p class="small text-muted">
                        Логотип Laravel и другие сопутствующие товарные знаки принадлежат их законным
                        владельцам.
                    </p>
                </div>

                <div class="col-6 col-md-auto">
                    <p class="fw-normal text-white">Ресурсы сообщества</p>

                    <div class="navbar navbar-dark">
                        <ul class="nav flex-column navbar-nav">
                            <li class="nav-item mb-2">
                                <a href="{{ route('home') }}" class="nav-link p-0">Домашняя страница</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('feature') }}" class="nav-link p-0">Возможности</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('meets') }}" class="nav-link p-0">Мероприятия</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('ecosystem') }}" class="nav-link p-0">Экосистема</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('packages') }}" class="nav-link p-0">Пакеты</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('jobs') }}" class="nav-link p-0">Вакансии</a>
                            </li>
                            {{--
                            <li class="nav-item mb-2">
                                <a href="{{ route('resources') }}" class="nav-link p-0">Ресурсы</a>
                            </li>
                            --}}
                        </ul>
                    </div>
                </div>

                <div class="col-6 col-md-auto">
                    <p class="fw-normal text-white">Взаимодействие</p>
                    <div class="navbar navbar-dark">
                        <ul class="nav flex-column navbar-nav">
                            <li class="nav-item mb-2">
                                <a href="{{ route('advertising') }}" class="nav-link p-0">Партнёрство</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('team') }}" class="nav-link p-0">Команда</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('status') }}" class="nav-link p-0">Статус переводов</a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ asset('https://github.com/laravel-russia') }}" class="nav-link p-0">
                                    Исходный код
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ asset('https://github.com/laravel-russia') }}" class="nav-link p-0">
                                    Сообщить о проблеме
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="{{ asset('https://github.com/LaravelRUS/chat') }}"
                                   class="nav-link p-0">Правила сообщества</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-12 col-md-auto">
                    <div class="navbar navbar-dark">
                        <div class="nav flex-column">
                            <form data-controller="theme" data-action="change->theme#toggleTheme" data-turbo-permanent
                                  class="btn-group" role="group" aria-label="Тема оформления" id="theme-checker-group">
                                <input type="radio" value="auto" data-theme-target="preferred" class="btn-check"
                                       name="theme-checker" id="theme-checker-auto" autocomplete="off" checked>
                                <label class="btn btn-outline-secondary" for="theme-checker-auto">
                                    <x-icon path="bs.circle-half"/>
                                </label>

                                <input type="radio" value="light" data-theme-target="preferred" class="btn-check"
                                       name="theme-checker" id="theme-checker-light" autocomplete="off">
                                <label class="btn btn-outline-secondary" for="theme-checker-light">
                                    <x-icon path="bs.sun-fill"/>
                                </label>

                                <input type="radio" value="dark" data-theme-target="preferred" class="btn-check"
                                       name="theme-checker" id="theme-checker-dark" autocomplete="off">
                                <label class="btn btn-outline-secondary" for="theme-checker-dark">
                                    <x-icon path="bs.moon-stars-fill"/>
                                </label>
                            </form>
                        </div>

                    </div>
                </div>
            </footer>
        </div>
    </div>


    {{-- @include('particles.mobile-menu') --}}
    @include('particles.back-to-top')
@endsection
