# Template for landing [slider](https://github.com/8Panteon8/slider) on [Winter cms](https://wintercms.com)


In folder `plugins` you can find usege plug for winter cms 

For install it:
```shell
php artisan winter:up
```



Photos uploaded to the slider have a handler `resize(2560, 1440, { mode: 'crop', quality: '80' })`



Also note the `theme.yamle` folder in `themes/superslider`, here is constructor theme.

>Is the code for the backend form builder below.

```yaml
form:
    tabs:
        fields:
            logo_text:
                label: Компания
                span: auto
                comment: Введите название вашей компании
                tab: Основные
            logo_image:
                label: Логотип
                type: mediafinder
                mode: image
                tab: Основные
                span: auto
            phone:
                label: Телефон
                span: auto
                tab: Основные
            address:
                label: Адресс
                span: auto
                tab: Основные
            soc:
                label: Социальыне сети
                comment: Ссылки на социальные сети компании
                type: repeater
                span: auto
                tab: Основные
                form:
                    fields:
                        image:
                            label: Логотип социальной сети
                            type: mediafinder
                            mode: image
                            span: auto
                        link:
                            label: Ссылка на страницу
                            span: auto
            share:
                label: Шеринг в соц. сетях
                comment: Ссылки на шеринг
                type: repeater
                span: auto
                tab: Основные
                form:
                    fields:
                        image:
                            label: Логотип социальной сети
                            type: mediafinder
                            mode: image
                            span: auto
                        link:
                            label: Ссылка на страницу
                            span: auto
            landing_1_title:
                label: title лендинга
                tab: Лендинг "Вездеход"
            landing_1_description:
                label: description лендинга
                tab: Лендинг "Вездеход"
                type: textarea
            landing_1_slider:
                labale: Слайдер
                type: repeater
                tab: Лендинг "Вездеход"
                form:
                    fields:
                        image:
                            label: Изображение
                            type: mediafinder
                            mode: image
                        header:
                            label: Заголовок
                        description:
                            type: textarea
                            label: Описание
```
 

