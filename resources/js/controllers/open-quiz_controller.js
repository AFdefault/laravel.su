import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = [ "output" ];

    /**
     * @type {number}
     */
    connect() {
        this.step = 0;
    }

    /**
     *
     * @param event
     */
    greet(event) {

        event.target.classList.add('disabled');

        let dragon = `
                                                  .~))>>
                                                 .~)>>
                                               .~))))>>>
                                             .~))>>             ___
                                           .~))>>)))>>      .-~))>>
                                         .~)))))>>       .-~))>>)>
                                       .~)))>>))))>>  .-~)>>)>
                   )                 .~))>>))))>>  .-~)))))>>)>
                ( )@@*)             //)>))))))  .-~))))>>)>
              ).@(@@               //))>>))) .-~))>>)))))>>)>
            (( @.@).              //))))) .-~)>>)))))>>)>
          ))  )@@*.@@ )          //)>))) //))))))>>))))>>)>
       ((  ((@@@.@@             |/))))) //)))))>>)))>>)>
      )) @@*. )@@ )   (\\_(\\-\\b  |))>)) //)))>>)))))))>>)>
    (( @@@(.@(@ .    _/\`-\`  ~|b |>))) //)>>)))))))>>)>
     )* @@@ )@*     (@)  (@) /\\b|))) //))))))>>))))>>
   (( @. )@( @ .   _/  /    /  \\b)) //))>>)))))>>>_._
    )@@ (@@*)@@.  (6///6)- / ^  \\b)//))))))>>)))>>   ~~-.
 ( @jgs@@. @@@.*@_ VvvvvV//  ^  \\b/)>>))))>>      _.     \`bb
  ((@@ @@@*.(@@ . - | o |' \\ (  ^   \\b)))>>        .'       b\`,
   ((@@).*@@ )@ )   \\^^^/  ((   ^  ~)_        \\  /           b \`,
     (@@. (@@ ).     \`-'   (((   ^    \`\\ \\ \\ \\ \\|             b  \`.
       (*.@*              / ((((        \\| | |  \\       .       b \`.
                         / / (((((  \\    \\ /  _.-~\\     Y,      b  ;
                        / / / (((((( \\    \\.-~   _.\`" _.-~\`,    b  ;
                       /   /   \`(((((()    )    (((((~      \`,  b  ;
                     _/  _/      \`"""/   /'                  ; b   ;
                 _.-~_.-~           /  /'                _.'~bb _.'
               ((((~~              / /'              _.'~bb.--~
                                  ((((          __.-~bb.-~
                                              .'  b .~~
                                              :bb ,'
                                              ~~~~


Вы прерываете мой покой!

Я - Мистический дракон и не собираюсь вмешиваться в твое испытание.
Однако, по просьбе Василисы, я согласился помочь, хотя, должен признать, что это не особо вдохновляет меня.
Вместо этого давай поговорим о чем-то более интересном.

У меня есть дальний родственник - Змей Горыныч, который обладает тремя головами!
Весьма впечатляюще, не так ли? Интересно, способен ли он, подобно римскому императору, одновременно выполнять три задачи?
Это весьма любопытный вопрос, открывающий нам множество возможностей для размышлений.

<%%%%|==========>
Все иди от сюда, больше тебе нечего тут делать!
`;


        console.warn(dragon);
    }
}