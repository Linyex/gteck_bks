
<style>
    .panel1,.panel2,.panel3,.panelt101,.panelt111,.panele101,.panely101{
        display: none;
    }
    .panel{
        display: none;
    }
    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 9999;
        display: none;
    }
    .popup {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .inpass {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin: 10px;
        width: 200px;
    }
    .btnpass {
        padding: 10px 20px;
        margin: 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background: #007bff;
        color: white;
    }
    .btnpass:hover {
        background: #0056b3;
    }
</style>

<form onsubmit="return PassCheck(document)" method="post" id="pop">
<div id="overlay">
    <div class="popup">
        <a>Введите пароль своей группы для получения доступа к УМК<br></a>
        <input type="text" class="inpass" name="inpass" id="pass">
        <button type="submit" class='btnpass'>Отправить</button>

    </div>
</div>
</form>

<h2>УМК</h2>
<hr>
<div style="display: table; width: 100%; word-break: break-word;">
    <!-- BEGIN: 1-I KYRS -->
    <div class="panel1" style="margin-bottom: 10px;" id="panel1">
        <a class="collapsed" data-toggle="collapse" href="#cl" aria-expanded="false" style="color: #fff;">
            <div class="panel-heading" name="first-course" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">1-й курс</div>
        </a>
        <div id="cl" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">

                <div class="panel" style="margin-bottom: 0px;" id = "panelt111">
                    <a class="collapsed" data-toggle="collapse" href="#cdl" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading"  style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-111</div>
                    </a>

                    <div id="cdl"  class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-111'>
                                <ul style="padding: 0; line-height: 30px;">
                                <li><a href="/assets/files/ymk/ЭУМК _Основы предпринимательства Т-101, 111, 201, 211, Э-101,201.doc">Основы предпринимательства</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Охрана труда Т-101,111, Э-101.docx">Охрана труда</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Экспертиза товаров Т 111, 211, 201, 301.docx">Экспертиза товаров</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Информ технол на базе ПТО Т 111, 211 .doc">Деловые коммуникации</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК ЭКОНОМИКА Т-111 проф-тех образование.docx">Экономика организации</a></li>
                                <li><a href="/assets/files/ymk/УМК ДАНИЛЕВИЧ ДК Т101,Т111,Э201.docx">Информационные технологии</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Основы менеджмента Т-111.docx">Основы менджмента</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-201, Э301, Т-111, Т-201 т211 Ценообразование.docx">Ценообразование</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК т-111.docx">Иностранный язык</a></li>
                                <li><a href="/assets/files/ymk/оп ЭУМКт101 т111 э101.docx">Основы права</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Коммерческая деятельность Т-111, 211, 201, 301.doc">Коммерческая деятельность</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Основы маркетинга Т-111, 201, 211, Э-101, 201, Б-301.docx">Основы маркетинга</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_ОТТ (на основе ПТО) Т 111, Т 211.docx">ОТТ (на основе ПТО)</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Торговое оборудование Т 111, Т 101.docx">Торговое оборудование</a></li>
                                <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК История беларусской государственности 1 курс.docx">История беларусской государственности</a></li>
                                <li><a href="/assets/files/ymk/эумк тавароведение Т-111, Т-211.docx">Товароведение</a></li>
                                
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id ="panelt101">
                    <a class="collapsed" data-toggle="collapse" href="#cd2" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-101</div>
                    </a>
        
                    <div id="cd2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;" >
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-101'>
                                <ul style="padding: 0; line-height: 30px;">
                                <li><a href="/assets/files/ymk/ЭУМК _Основы предпринимательства Т-101, 111, 201, 211, Э-101,201.doc">Основы предпринимательства</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Охрана труда Т-101,111, Э-101.docx">Охрана труда</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК ЭКОНОМИКА Т-101 .docx">Экономика организации</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Товароведение продовольственных товаров т101,201,301.docx">Товароведение продовольственных</a></li>
                                <li><a href="/assets/files/ymk/эумк тавароведение Т-101, Т-201.docx">Товароведение</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Информационные технологии Т 101, 201.docx">Информационные технологии</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Основы менеджмента Т-101.docx">Основы менджмента</a></li>
                                <li><a href="/assets/files/ymk/УМК ДАНИЛЕВИЧ ДК Т101,Т111,Э201.doc">Деловые коммуникации</a></li>
                                <li><a href="/assets/files/ymk/оп ЭУМКт101 т111 э101.docx">Основы права</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Основы мерчендайзинга Т-101, 211.docx">Основы мерчендайзинга</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК э-101. Т-101.docx">Иностранный язык</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Торговое оборудование Т 111, Т 101.docx">Торговое оборудование</a></li>
                                <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Бухгалтерский учет Т-101-201.docx">Бухгалтерский учет</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК История беларусской государственности 1 курс.docx">История беларусской государственности</a></li>
                                                            
                                </ul>
                                    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id ="panele101" class="e101">
                    <a class="collapsed" data-toggle="collapse" href="#cd3" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Э-101</div>
                    </a>

                    <div id="cd3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;" >
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-e-101'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/ymk/"> </a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК Э-101, 201, 301Экономика организации.docx">Экономика организации</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК Основы менеджмента Э-101.docx">Основы менджмента</a></li>
                                    <li><a href="/assets/files/ymk/оп ЭУМКт101 т111 э101.docx"></a>Основы права</li>
                                    <li><a href="/assets/files/ymk/ЭУМК Э-101 Статистика.doc">Статистика</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК э-101. Т-101.docx">Иностранный язык</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК_Охрана труда Т-101,111, Э-101.docx">Охрана труда</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК _Основы предпринимательства Т-101, 111, 201, 211, Э-101,201.doc">Основы предпринимательства</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК _Охрана окружающей среды и энергосбережение Т-211, Э-101, 201, Ю-201.doc">Охрана окружающей среды и энергосбережение</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК_Основы маркетинга Т-111, 201, 211, Э-101, 201, Б-301.docx">Основы маркетинга</a></li>
                                    <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК Бухгалтерский учет Э101.docx">Бухгалтерский учет</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК Финансы организации Э-101 .docx">Финансы организации</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК История беларусской государственности 1 курс.docx">История беларусской государственности</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panely101" class ="y101">
                    <a class="collapsed" data-toggle="collapse" href="#cd4" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Ю-101</div>
                    </a>

                    <div id="cd4" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-y-101'>
                                <ul style="padding: 0; line-height: 30px;">
                                <li><a href="/assets/files/ymk/ЭУМК Конституционное право заочное отделение правоведение гр Ю 101.docx">Конституционное право </a></li>
                                <li><a href="/assets/files/ymk/ЭУМК основы экономики ю101.docx">ЭУМК основы экономики ю101</a></li>
                                <li><a href="/assets/files/ymk/эумк суд 5.docx">судоустройство</a></li>
                                <li><a href="/assets/files/ymk/эумк по гп.docx">Гражданское право</a></li>
                                <li><a href="/assets/files/ymk/эумк по ап-.docx">Административное право</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК логика.docx">Логика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Ю-101.docx">Иностранный язык</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК история государства и права.docx">История государства и права</a></li>
                                <li><a href="/assets/files/ymk/БЭУМК Ю-101 Документационное обеспечение управления.docx">Документационное обеспечение управления</a></li>
                                <li><a href="/assets/files/ymk/эумк мпп.docx">Международное публичное право</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК по АДП не готов.docx">Административное право</a></li>
                                <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                <li><a href="/assets/files/ymk/Культура речи ЭУМК Ю-101.pdf">Культура речи</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК по ОТП Ю-101.docx">Общая теория права</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК История беларусской государственности 1 курс.docx">История беларусской государственности</a></li>
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
    <div class="panel2" style="margin-bottom: 10px;" id = "panel2">
        <a class="collapsed" data-toggle="collapse" href="#c2" style="color: #fff;">
            <div class="panel-heading" name="second-course" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">2-й курс</div>
        </a>

        <div id="c2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                <div class="panel" style="margin-bottom: 0px;" id ="panelt211" class="t211">
                    <a class="collapsed" data-toggle="collapse" href="#cel" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-211</div>
                    </a>

                    <div id="cel" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-211'>
                                <ul style="padding: 0; line-height: 30px;">
                                <li><a href="/assets/files/ymk/ЭУМК _Внешнеэкономическая деятельностьТ-201, 211, 301, Э-201, 301, Б-301.doc">Внешнеэкономическая деятельность</a></li>
                                <li><a href="/assets/files/ymk/эумк тавароведение Т-111, Т-211.docx">Товароведение</a></li>
                                <li><a href="/assets/files/ymk/УМК ДАНИЛЕВИЧ ДК Т101,Т111,Э201.docx">Информационные технологии</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-201, Э301, Т-111, Т-201 т211 Ценообразование.docx">Ценообразование</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Экспертиза товаров Т 111, 211, 201, 301.docx">Экспертиза товаров</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Основы мерчендайзинга Т-101, 211.docx">Основы мерчендайзинга</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Основы предпринимательства Т-101, 111, 201, 211, Э-101,201.doc">Основы предпринимательства</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Охрана окружающей среды и энергосбережение Т-211, Э-101, 201, Ю-201.doc">Охрана окружающей среды и энергосбережение</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Коммерческая деятельность Т-111, 211, 201, 301.doc">Коммерческая деятельность</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Основы логистики Т 201, Т 211.docx">Основы логистики</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Основы маркетинга Т-111, 201, 211, Э-101, 201, Б-301.docx">Основы маркетинга</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_ОТТ (на основе ПТО) Т 111, Т 211.docx">ОТТ (на основе ПТО)</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Товарный менеджмент ПТО Т 211.docx">Товарный менеджмент ПТО </a></li>
                                <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Финансы организации Т-211 .docx">Финансы организации</a></li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panelt201" class ="t201">
                    <a class="collapsed" data-toggle="collapse" href="#ce2" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-201</div>
                    </a>

                    <div id="ce2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-201'>
                                <ul style="padding: 0; line-height: 30px;">
                                <li><a href="/assets/files/ymk/ЭУМК _Внешнеэкономическая деятельностьТ-201, 211, 301, Э-201, 301, Б-301.doc">Внешнеэкономическая деятельность</a></li>
                                <li><a href="/assets/files/ymk/эумк тавароведение Т-101, Т-201.docx">Товароведение</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Экспертиза товаров Т 111, 211, 201, 301.docx">Экспертиза товаров</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК ОСНОВЫ МЕНЕДЖМЕНТА Т-201.docx">Основы менеджмента</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Товароведение продовольственных товаров т101,201,301.docx">Товароведение продовольственных</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Защита населения и территорий от ЧС Т-201, Э-201, Ю-201.docx">Защита населения и территорий от ЧС</a></li>    
                                <li><a href="/assets/files/ymk/ЭУМК_Информационные технологии Т 101, 201.docx">Информационные технологии</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Учебн Практ Информац технолог Т 201.docx">Информационные технологии учебная практика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-201, Э301, Т-111, Т-201 т211 Ценообразование.docx">Ценообразование</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Основы предпринимательства Т-101, 111, 201, 211, Э-101,201.doc">Основы предпринимательства</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Коммерческая деятельность Т-111, 211, 201, 301.doc">Коммерческая деятельность</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Основы логистики Т 201, Т 211.docx">Основы логистики</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Основы маркетинга Т-111, 201, 211, Э-101, 201, Б-301.docx">Основы маркетинга</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_ОТТ (на основе ОБО, ОСО) Т 201, Т 301.docx">ОТТ (на основе ОБО, ОСО)</a></li>
                                <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Бухгалтерский учет Т-111.docx">Бухгалтерский учет</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Финансы организации Т-201 .docx">Финансы организации</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panele201" class ="e201">
                    <a class="collapsed" data-toggle="collapse" href="#ce3" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Э-201</div>
                    </a>

                    <div id="ce3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-e-201'>
                                <ul style="padding: 0; line-height: 30px;">
                                <li><a href="/assets/files/ymk/ЭУМК Э-101, 201, 301Экономика организации.docx">Экономика организации</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-201-301 ОРГАНИЗАЦИЯ ПРОИЗВОДСТВА 5-04-0311-01.docx">ОРГАНИЗАЦИЯ ПРОИЗВОДСТВА 5-04-0311-01</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК ОСНОВЫ МЕНЕДЖМЕНТА Э-201.docx">Основы менеджмента</a></li>
                                <li><a href="/assets/files/ymk/УМК ДАНИЛЕВИЧ ДК Т101,Т111,Э201.doc">Деловые коммуникации</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-201, Э301, Т-111, Т-201 т211 Ценообразование.docx">Ценообразование</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-301 201 ОРГАНИЗАЦИЯ ПРОИЗВОДСТВА 2-27 01 01.docx">ОРГАНИЗАЦИЯ ПРОИЗВОДСТВА 2-27 01 01</a></li>
                                <li><a href="/assets/files/ymk/ИТ Э-201.docx">Информационные технологии</a></li>
                                <li><a href="/assets/files/ymk/Бухгалтерский учет в торговле Б-301, Э-201.doc">Бухгалтерский учет в торговле</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Внешнеэкономическая деятельностьТ-201, 211, 301, Э-201, 301, Б-301.doc">Внешнеэкономическая деятельность</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Защита населения и территорий от ЧС Т-201, Э-201, Ю-201.docx">Защита населения и территорий от ЧС</a></li>  
                                <li><a href="/assets/files/ymk/ЭУМК _Основы предпринимательства Т-101, 111, 201, 211, Э-101,201.doc">Основы предпринимательства</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Охрана окружающей среды и энергосбережение Т-211, Э-101, 201, Ю-201.doc">Охрана окружающей среды и энергосбережение</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Основы маркетинга Т-111, 201, 211, Э-101, 201, Б-301.docx">Основы маркетинга</a></li>
                                <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Финансы организации Э-201 .docx">Финансы организации</a></li>
                                
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;"id = "panely201" class ="y201">
                    <a class="collapsed" data-toggle="collapse" href="#ce4" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Ю-201</div>
                    </a>

                    <div id="ce4" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-y-201'>
                                <ul style="padding: 0; line-height: 30px;">
                                <li><a href="/assets/files/ymk/ЭУМК Уголовное право заочное отделение правоведене гр.Ю 201.docx">Уголовное право</a></li> 
                                <li><a href="/assets/files/ymk/эумк суд 5.docx">судоустройство</a></li>
                                <li><a href="/assets/files/ymk/эумк по гп.docx">Гражданское право</a></li>
                                <li><a href="/assets/files/ymk/эумк по ап-.docx">Административное право</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК по АДП не готов.docx">Административное право</a></li>
                                <li><a href="/assets/files/ymk/фп ЭУМК э301 ю201.docx">Финансовое право</a></li>
                                <li><a href="/assets/files/ymk/хп ЭУМК ю201 э301.docx">Хозяйственное право</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Право на социальное обеспечение и охрану здоровья заочное отделение правоведение гр Ю 201.docx">Право на социальное обеспечение и охрану здоровья</a></li>
                                <li><a href="/assets/files/ymk/ИТ Ю-201.docx">Информационные технологии</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Защита населения и территорий от ЧС Т-201, Э-201, Ю-201.docx">Защита населения и территорий от ЧС</a></li> 
                                <li><a href="/assets/files/ymk/эумк мпп.docx">Международное публичное право</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Охрана окружающей среды и энергосбережение Т-211, Э-101, 201, Ю-201.doc">Охрана окружающей среды и энергосбережение</a></li>
                                <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Хозяйственный процесс заочное отделение правоведение гр Ю 201.pdf">Хозяйственный процесс</a></li>
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
    <div class="panel3" style="margin-bottom: 10px;" id = "panel3">
        <a class="accordion-toggle collapsed" data-toggle="collapse" href="#c3" style="font-size: 18px; color: #fff;">
            <div class="panel-heading" name="third-course" style="border: solid #c3c3c3 1px; font-size: 18px; font-weight: 800; background: rgb(64, 123, 154);">3-й курс</div>
        </a>

        <div id="c3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                <div class="panel" style="margin-bottom: 0px;" id = "panelt301" class ="t301">
                    <a class="collapsed" data-toggle="collapse" href="#crl" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Т-301, </div>
                    </a>

                    <div id="crl" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-t-301'>
                                <ul style="padding: 0; line-height: 30px;">
                                    <li><a href="/assets/files/ymk/ЭУМК_Коммерческая деятельность Т-111, 211, 201, 301.doc">Коммерческая деятельность</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК_Экспертиза товаров Т 111, 211, 201, 301.docx">Экспертиза товаров</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК_ОТТ (на основе ОБО, ОСО) Т 201, Т 301.docx">ОТТ (на основе ОБО, ОСО)</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК_Товароведение продовольственных товаров т101,201,301.docx">Товароведение продовольственных</a></li>
                                    <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                    <li><a href="/assets/files/ymk/ЭУМК Финансы организации Т-301 .docx">Финансы организации</a></li>

                                   
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panele301" class ="e301">
                    <a class="collapsed" data-toggle="collapse" href="#cr2" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Э-301</div>
                    </a>

                    <div id="cr2" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-e-301'>
                                <ul style="padding: 0; line-height: 30px;">
                                <li><a href="/assets/files/ymk/ЭУМК Э-101, 201, 301Экономика организации.docx">Экономика организации</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-201-301 ОРГАНИЗАЦИЯ ПРОИЗВОДСТВА 5-04-0311-01.docx">ОРГАНИЗАЦИЯ ПРОИЗВОДСТВА 5-04-0311-01</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК АХД Э-301.docx">Анализ хозяйственной деятельности</a></li>
                                <li><a href="/assets/files/ymk/фп ЭУМК э301 ю201.docx">Финансовое право</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-301 Бизнес планирование.docx">Бизнес планирование</a></li>
                                <li><a href="/assets/files/ymk/хп ЭУМК ю201 э301.docx">Хозяйственное право</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-201, Э301, Т-111, Т-201 т211 Ценообразование.docx">Ценообразование</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-301 201 ОРГАНИЗАЦИЯ ПРОИЗВОДСТВА 2-27 01 01.docx">ОРГАНИЗАЦИЯ ПРОИЗВОДСТВА 2-27 01 01</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-301 Технологическая и преддипломная практика.docx">Технологическая и преддипломная практика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Э-301 Экономика торговли.docx">Экономика торговли</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК _Внешнеэкономическая деятельностьТ-201, 211, 301, Э-201, 301, Б-301.doc">Внешнеэкономическая деятельность</a></li>
                                <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Налогообложение Э-301.docx">Налогообложение</a></li>

                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel" style="margin-bottom: 0px;" id = "panelb301">
                    <a class="collapsed" data-toggle="collapse" href="#cr3" aria-expanded="false" style="color: #fff;">
                        <div class="panel-heading" style="border: solid #c3c3c3 1px; font-size: 18px; background: #3fac3f;">Группа Б-301</div>
                    </a>

                    <div id="cr3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body" style="border: solid #c3c3c3 1px; border-top: 0;">
                            <div class="spiski" name = 'spiski-b-301'>
                                <ul style="padding: 0; line-height: 30px;">
                                <li><a href="/assets/files/ymk/ЭУМК _Внешнеэкономическая деятельностьТ-201, 211, 301, Э-201, 301, Б-301.doc">Внешнеэкономическая деятельность</a></li>
                                <li><a href="/assets/files/ymk/Бухгалтерский учет в торговле Б-301, Э-201.doc">Бухгалтерский учет в торговле</a></li>
                                <li><a href="/assets/files/ymk/Проверка и контроль группа Б-301.doc">Проверка и контроль группа</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК_Основы маркетинга Т-111, 201, 211, Э-101, 201, Б-301.docx">Основы маркетинга</a></li>
                                <li><a href="/assets/files/ymk/Бел яз проф ЭУМК все группы.pdf">Белорусский язык профлексика</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Бухгалтерский учет Б-301.docx">Бухгалтерский учет</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Налогообложение Б-301.docx">Налогообложение</a></li>
                                <li><a href="/assets/files/ymk/ЭУМК Преддипломная практика Б-301.docx">Преддипломная практика</a></li>
                             

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: 3-I KYRS -->
</div>

<script type="text/javascript">
        var delay_popup = 0;
        setTimeout("document.getElementById('overlay').style.display='block'", delay_popup);

function PassCheck(form) {
    var password = form.inpass.value;
    
    // Проверяем пароли для разных групп
    if(password == "111") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panelt111").style.display = "inline";
        return false;
    }
    else if(password == "101") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panelt101").style.display = "inline";
        return false;
    }
    else if(password == "э101") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panele101").style.display = "inline";
        return false;
    }
    else if(password == "ю101") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel1").style.display = "inline";
        document.getElementById("panely101").style.display = "inline";
        return false;
    }
    else if(password == "211") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panelt211").style.display = "inline";
        return false;
    }
    else if(password == "201") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panelt201").style.display = "inline";
        return false;
    }
    else if(password == "э201") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panele201").style.display = "inline";
        return false;
    }
    else if(password == "ю201") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel2").style.display = "inline";
        document.getElementById("panely201").style.display = "inline";
        return false;
    }
    else if(password == "301") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel3").style.display = "inline";
        document.getElementById("panelt301").style.display = "inline";
        return false;
    }
    else if(password == "э301") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel3").style.display = "inline";
        document.getElementById("panele301").style.display = "inline";
        return false;
    }
    else if(password == "б301") {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("panel3").style.display = "inline";
        document.getElementById("panelb301").style.display = "inline";
        return false;
    }
    else {
        alert("Неверный пароль!");
        return false;
    }
}
</script>



