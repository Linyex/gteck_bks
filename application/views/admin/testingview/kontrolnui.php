<?php echo $header ?>
<!-- <style>
.panel{
display: none;
}
</style>
<form action="/../../engine/kontraction/kolntolnui_action_pass.php" method="post" enctype="multipart/form-data" name="form-kontr-actin">
<div id="overlay">
    /<div class="popup">
        <h2>Авторизация</h2>
        <p>Введите пароль который вам выдали для просмотра контрольных работ</p>
        <input type="text" class="inpass" name="inpass">
    </div>
</div>
</form>
-->
<h2>Контрольные работы</h2>
<hr>
<div style="display: table; width: 100%; word-break: break-word;">
    <!-- BEGIN: 1-I KYRS -->
    <div class="panel" style="margin-bottom: 10px;">
        <a class="collapsed" data-toggle="collapse" href="#cl" aria-expanded="false" style="color: #fff;">
            <div class="panel-heading" name="first-course" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">1-й курс</div>
        </a>
        <div id="cl" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">

                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#cdl" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading"  style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-111</div>
                    </a>

                    <div id="cdl" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-111'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/История белорусской государственности домашняя контрольная зо.docx">История белорусской государственности</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Т-111 Эк орг 2023 Дакука.docx">Экономика организации</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР БУ Т111.pdf">Бухгалтерский учет</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР №1 Т-111.pdf">Иностранный язык (проф. лексика)</a></li>
                                    <li><a href="/assets/files/konrolnui/Осн.Мендж.ДКР Т-111 2024.docx">Основы менеджмента</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Осн маркет Б301,Э201,Т201,Т211,Т111,Э101.rtf">Основы маркетинга</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Охрана труда Т-101, Т-111, Э-101 2023.pdf">Охрана труда</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР СиС 2023-2024.docx">Стандартизация и сертификация</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Т111 Товароведение.docx">Товароведение</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР  Осн предпинимат Б201,Т101,Т111.rtf">Основы предпринимательства</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#cd2" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-101</div>
                    </a>

                    <div id="cd2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-101'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/История белорусской государственности домашняя контрольная зо.docx">История белорусской государственности</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР ТО Т-101 2023-2024.docx">Торговое оборудование</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР-2  Эк орг Т-101 2023.docx">Экономика организации</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР №1 Э-101, Т-101.pdf">Иностранный язык(проф. лексика)</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Основы права.doc">Основы права</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Охрана труда Т-101, Т-111, Э-101 2023.pdf">Охрана труда</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР СиС 2023-2024.docx">Стандартизация и сертификация</a></li>
                                   
                                    <li><a href="/assets/files/konrolnui/ДКР  Осн предпинимат Б201,Т101,Т111.rtf">Основы предпринимательства</a></li>
                                </ul>
                                    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#cd3" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Э-101</div>
                    </a>

                    <div id="cd3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-e-101'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/История белорусской государственности домашняя контрольная зо.docx">История белорусской государственности</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Охрана труда Т-101, Т-111, Э-101 2023.pdf">Охрана труда</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР СиС 2023-2024.docx">Стандартизация и сертификация</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Э 101(2023-2024).pdf">Бухгалтерский учет</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР №1 Э-101, Т-101.pdf">Иностранный язык(проф.лексика)</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Основы права.doc">Основы права</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Осн маркет Б301,Э201,Т201,Т211,Т111,Э101.rtf">Основы маркетинга</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Статистика Э-101 2023-2024.pdf">Статистика</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Экономика Э-101 2023-2024.pdf">Экономика организации</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#cd4" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Ю-101</div>
                    </a>

                    <div id="cd4" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-y-101'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/История белорусской государственности домашняя контрольная зо.docx">История белорусской государственности</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР №1 Общая теория права Ю-101.doc">Общая теория права</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Ю-101 2023.doc">Основы экономики</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР №1 Ю-101.pdf">Иностранный язык (проф. лексика)</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР история государства и права Беларуси.pdf">История государства и права</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР конституционное право.pdf">Конституационное право</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР судоустройство.pdf">Судоустройство</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: 1-I KYRS -->
    <!-- BEGIN: 2-I KYRS -->
    <div class="panel" style="margin-bottom: 10px;">
        <a class="collapsed" data-toggle="collapse" href="#c2" style="color: #fff;">
            <div class="panel-heading" name="second-course" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">2-й курс</div>
        </a>

        <div id="c2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#cel" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-211</div>
                    </a>

                    <div id="cel" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-211'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/ДКР Осн маркет Б301,Э201,Т201,Т211,Т111,Э101.rtf">Основы маркетинга</a></li>
                                    <li><a href="/assets/files/konrolnui/Осн.Мендж.ДКР Т-211 2023.docx">Основы менеджмента</a></li>
                                    <li><a href="">Организация и технология торговли(курсовая работа)</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР дляТ-211,Т-201 по ЦО 23-24.docx">Ценообразование</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР финансы орг. Т-211 2023-2024.doc">Финансы организации</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР КД  Т 211, Т 301 2023.docx">Коммерческая деятельность</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР  ВЭД  Т211,Т301,Б301,Э301 2023.docx">Внешнеэкономическая деятельность</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Товароведение прод товаров  Т211 2023-2024.doc">Товароведение продовольственных товаров</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР  Т211 ТНТ  2023.doc">Товароведение непродовольственных товаров</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#ce2" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-201</div>
                    </a>

                    <div id="ce2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-201'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/ДКР для Т-201 Дакука 2023.docx">Экономика организации(ДКР №2)</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР для Т 201 2024-2025.pdf">Бухгалтерский учет</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР № 1 Товароведение прод товаров Т201  2023-2024.doc">Товароведение продовольственных товаров (ДКР №1)</a></li>
                                     <li><a href="/assets/files/konrolnui/ДКР  Основы маркетинга Э 201, Т 201, Т 211, Б 301 2022.docx">Основы маркетинга</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР№ 1 Орг и техн торг Т 201 2023.doc">Организация и технология торговли</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР дляТ-211,Т-201 по ЦО 23-24.docx">Ценообразование</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР-ТНТ гр. Т 201-2023.docx">Товароведение непродовольственных товаров (ДКР №1)</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#ce3" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Э-201</div>
                    </a>

                    <div id="ce3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-e-201'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/ДКР по эконом Э-201 ( на 2023-2024).pdf">Экономика организации</a></li>
                                    <li><a href="">Курсовая работа по экономике организации</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Э 201(2023-2024).doc">Бухгалтерский учёт</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР ИТ Э-201 2023-2024.doc">Информационные технологии</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Трудовое право.doc">Трудовое право</a></li>
                                    <li><a href="/assets/files/konrolnui/Авт уч Э-201_ДКР_2023-2024.pdf">Автоматизация учета</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Осн маркетинга Т 201, Т 211, Э 201,  Б 301  2021.rtf">Основы маркетинга</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР ЗО Психология  2022.doc">Психология и этика деловых отношений</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР по Орг произв Э201 2023-2024.doc">Организация производства</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР ФО экономисты 2023-2024 сайт.pdf">Финансы организации</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР СиС 2023-2024.docx">Стандартизация и сертификация</a></li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#ce4" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Б-201</div>
                    </a>

                    <div id="ce4" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-b-201'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/ДКР Экономика организации Б-201 2023-2024.pdf">Экономика организации</a></li>
                                    <li><a href="">Курсовая работа по экономике организации</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР ИТ Б-201 2023-2024.pdf">Информационные технологии</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР ЭКОНОМИКА б-201 23-24.docx">Экономика торговли</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР  Организация торговли Б 201 2023.doc">Организация торговли</a></li>
                                    <li><a href="/assets/files/konrolnui/Авт уч Б-201 БУ_ДКР_2023-2024.pdf">Автоматизация учета</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР ФиК Б-201 2023-24.docx">Финансы и кредит</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Бух.учет №1 Б-201 2023-24.pdf">Бухгалтерский учет</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Б-201 АХД 2022.pdf">Анализ хозяйственной деятельности</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР  Осн предпинимат Б201,Т101,Т111.rtf">Основы предпринимательства</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: 2-I KYRS -->
    <!-- BEGIN: 3-I KYRS -->
    <div class="panel" style="margin-bottom: 10px;">
        <a class="accordion-toggle collapsed" data-toggle="collapse" href="#c3" style="font-size: 18px; color: #fff;">
            <div class="panel-heading" name="third-course" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">3-й курс</div>
        </a>

        <div id="c3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#crl" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-301, </div>
                    </a>

                    <div id="crl" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-301'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/ДКР КД  Т 211, Т 301 2023.docx">Коммерческая деятельность</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР№ 2 Орг и техн торг Т 301 2023.doc">Организация и технология торговли</a></li>
                                    <li><a href="">Курсовая работа по организации и технологии торговли</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР  ВЭД  Т211,Т301,Б301,Э301 2023.docx">Внешнеэкономическая деятельность</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР ТНТ  301 - 2023 уч.год.doc">Товароведение непродовольственных товаров</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР финаны организаций Т-301 2023-2024.docx">Финансы организации</a></li>
                                    <li><a href="/assets/files/konrolnui/ПОКД   ДКР т-301 (4).doc">Правовое обеспечение коммерческой деятельности</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР №2 Товароведение прод товаров Т301 2023-2024. doc.doc">Товароведение продовольственных товаров</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#cr2" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Э-301</div>
                    </a>

                    <div id="cr2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-e-301'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/ДКР № 2 по ОП для Э-301, 2024-2025.doc">Организация производства</a></li>
                                    <li><a href="">Курсовая работа по организации производства</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР  ВЭД  Т211,Т301,Б301,Э301 2023.docx">Внешнеэкономическая деятельность</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР №2 ПРПД Э-301.doc">Правовое регулирование предпринимательской деятельности</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Цены Э-301 2023-2024.doc">Ценообразование</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР налоги экономисты 2023-2024 сайт.doc">Налогообложение</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР Финансовое право  2022.docx">Финансовое право</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР по ЭКОН.торг.Э-301 2023.pdf">Экономика торговли</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР по АХД гр.Э-301 2023-2024.docx">Анализ хозяйственной деятельности</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;">
                    <a class="collapsed" data-toggle="collapse" href="#cr3" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Б-301</div>
                    </a>

                    <div id="cr3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-b-301'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/konrolnui/ДКР  № 2   БУ 301 №2  2023-2024 .doc">Бухгалтерский учет</a></li>
                                    <li><a href="">Курсовая работа по бухгалтерскому учету</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР бух. учет торговля Б301  2023-24.docx">Бухгалтерский учет в торговле</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР  ВЭД  Т211,Т301,Б301,Э301 2023.docx">Внешнеэкономическая деятельность</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР  Основы маркетинга Э 201, Т 201, Т 211, Б 301 2022.docx">Основы маркетинга</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР налоги Б-301 2023-2024.doc">Налогообложение</a></li>
                                    <li><a href="/assets/files/konrolnui/ДКР-РИК-2023сайт.pdf">Проверка и контроль</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: 3-I KYRS -->
    <!-- BEGIN: KYRS LEKCII -->
    <div class="panel" style="margin-bottom: 10px;">
        <a class="accordion-toggle collapsed" data-toggle="collapse" href="#c4" style="font-size: 18px; color: #ffffff;">
            <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">Текст лекций</div>
        </a>

        <div id="c4" class="panel-collapse collapse" name="lectures" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                <div class="spiski" name = 'spiski'>
                    <ul style="padding: 0; line-height: 30px;">
                        <li><a href="/assets/files/konrolnui/file28.rar">Текст лекций по основам предпринимательства</a></li>
                        <li><a href="/assets/files/konrolnui/file29.rar">Текст лекций по основам маркетинга</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END: KYRS LEKCII -->
</div>



<script type="text/javascript">
        var delay_popup = 0;
        setTimeout("document.getElementById('overlay').style.display='block'", delay_popup);
</script>


<?php echo $footer ?>
