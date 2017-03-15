<?php
/**
 * Created by PhpStorm.
 * User: MeGa
 * Date: 09.11.2016
 * Time: 1:15
 */

require_once('template_head.php');

?>

<div id="fh5co-main">
    <div class="fh5co-intro text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h1 class="intro-lead">Имитационная модель СМО</h1>
                    <p class="">Воспользуйтесь меню ниже для рассчета модели и вывода результатов</p>
                </div>
            </div>
        </div>
    </div>


    <div id="fh5co-services">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="section-lead text-center">Исходные данные модели</h2>
                    <p>Рассмотрим двухканальную систему массового обслуживания (n = 2) с максимальной длиной очереди равной шести (m = 4). В СМО поступает простейший поток заявок со средней интенсивностью λ = 4,8 и показательным законом распределения времени между поступлением заявок. Поток обслуживаемых в системе заявок является простейшим со средней интенсивностью μ = 2 и показательным законом распределения временем обслуживания.</p>
                    <p>Для имитации СМО воспользуемся одним из методов статистического моделирования – имитационным моделированием. Будем использовать пошаговый подход. Суть этого подхода в том, что состояния системы рассматриваются в последующие моменты времени, шаг между которыми является достаточно малым, чтобы за его время произошло не более одного события.</p>
                    <p>Выберем шаг по времени (<img width="23" height="23" src="http://www.bestreferat.ru/images/paper/99/37/7393799.png" alt="">). Он должен быть много меньше среднего времени поступления заявки (<img width="18" height="29" src="http://www.bestreferat.ru/images/paper/00/38/7393800.png" alt="">) и среднего времени ее обслуживания (<img width="21" height="29" src="http://www.bestreferat.ru/images/paper/01/38/7393801.png" alt="">), т.е.</p>
                    <p><img width="85" height="29" src="http://www.bestreferat.ru/images/paper/02/38/7393802.png" alt=""></p>
                    <p><img width="242" height="54" src="http://www.bestreferat.ru/images/paper/03/38/7393803.png" alt=""></p>
                    <p><img width="142" height="54" src="http://www.bestreferat.ru/images/paper/04/38/7393804.png" alt=""></p>
                    <p>Определим шаг по времени<img width="98" height="26" src="http://www.bestreferat.ru/images/paper/05/38/7393805.png" alt="">.</p>
                    <p>Время поступления заявки в СМО и время ее обслуживания являются случайными величинами. Поэтому, при имитационном моделировании СМО их вычисление производится с помощью случайных чисел.</p>
                    <p>Рассмотрим поступление заявки в СМО. Вероятность того, что на интервале<img width="23" height="23" src="http://www.bestreferat.ru/images/paper/99/37/7393799.png" alt="">в СМО поступит заявка, равна:<img width="181" height="60" src="http://www.bestreferat.ru/images/paper/06/38/7393806.png" alt="">. Сгенерируем случайное число<img width="15" height="15" src="http://www.bestreferat.ru/images/paper/07/38/7393807.png" alt="">, и, если<img width="132" height="35" src="http://www.bestreferat.ru/images/paper/08/38/7393808.png" alt="">, то будем считать, что заявка на данном шаге в систему поступила, если<img width="132" height="35" src="http://www.bestreferat.ru/images/paper/09/38/7393809.png" alt="">, то не поступила.</p>
                    <p>В программе это осуществляет<b>isRequested</b><b>()</b>. Интервал времени<img width="23" height="23" src="http://www.bestreferat.ru/images/paper/99/37/7393799.png" alt="">примем постоянным и равным 0,0001, тогда отношение<img width="41" height="23" src="http://www.bestreferat.ru/images/paper/10/38/7393810.png" alt="">будет равно 10000. Если заявка поступила, то она принимает значение «истина», в противном случае значение «ложь».</p>
                    <p>Рассмотрим теперь обслуживание заявки в СМО. Время обслуживания заявки в системе определяется выражением<img width="150" height="54" src="http://www.bestreferat.ru/images/paper/11/38/7393811.png" alt="">, где<img width="18" height="19" src="http://www.bestreferat.ru/images/paper/12/38/7393812.png" alt="">– случайное число. В программе время обслуживания определяется с помощью функции<b>GetServiceTime</b><b>()</b>.</p>
                    <p>Алгоритм метода имитационного моделирования можно сформулировать следующим образом. Время работы СМО (<i>Т</i>) разбивается на шаги по времени<i>dt</i>, на каждом из них выполняется ряд действий. Вначале определяются состояния системы (занятость каналов, длина очереди), затем, с помощью функции<b>isRequested</b><b>()</b>, определяется, поступила ли на данном шаге заявка или нет.</p>
                    <p>Если поступила, и, при этом имеются свободные каналы, то с помощью функции<b>GetServiceTime</b><b>()</b>генерируем время обработки заявки и ставим ее на обслуживание. Если все каналы заняты, а длина очереди меньше 4, то помещаем заявку в очередь, если же длина очереди равна 4, то заявке будет отказано в обслуживании.</p>
                    <p>В случае, когда на данном шаге заявка не поступала, а канал обслуживания освободился, проверяем, есть ли очередь. Если есть, то из очереди заявку ставим на обслуживание в свободный канал. После проделанных операций время обслуживания для занятых каналов уменьшаем на величину шага<i>dt</i>.</p>
                    <p>По истечении времени<i>Т</i>, т.е., после моделирования работы СМО, вычисляются показатели эффективности работы системы и результаты выводятся на экран.</p>
                    <h2 class="section-lead text-center">Блок-схема программы</h2>
                    <p><img width="489" height="746" src="http://www.bestreferat.ru/images/paper/13/38/7393813.png" alt=""></p>
                    <p><img width="320" height="319" src="http://www.bestreferat.ru/images/paper/14/38/7393814.png" alt=""></p>
                    <p><u>Задание состояний системы.</u>Выделим у данной 2-х канальной системы 7 различных состояний: S<sub>0</sub>, S<sub>1</sub>. S<sub>6</sub>. СМО находится в состоянии S<sub>0</sub>, когда система свободна; S<sub>1</sub>– хотя бы один канал свободен; в состоянии S<sub>2</sub>, когда все каналы заняты, и есть место в очереди; в состоянии S<sub>6</sub>– все каналы заняты, и очередь достигла максимальной длины (queueLength = 4).</p>
                    <p><u>Изменение времени пребывания СМО в состояниях с длиной очереди 1, 2,3,4.</u>Это реализуется следующим программным кодом:</p>
                    <p>if ($queueLength &gt; 0)</p>
                    <p>{</p>
                    <p>$timeInQueue[$queueLength– 1] += $timeStep;</p>
                    <p>if ($queueLength &gt; 1)</p>
                    <p>{$timeInQueue [$queueLength – 2] += $timeStep;}</p>
                    <p>}</p>
                    <p>Присутствует такая операция, как помещение заявки на обслуживание в свободный канал. Просматриваются, начиная с первого, все каналы, когда выполняется условие timeOfFinishProcessingReq<b>[</b><b>i</b><b>] &lt;= 0</b>(канал свободен), в него подается заявка, т.е. генерируется время окончания обслуживания заявки.</p>
                    <p>Наиболее важными являются такие показатели, как:</p>
                    <p>1) Вероятность отказа в обслуживании заявки, т.е. вероятность того, что заявка покидает систему не обслуженной.В нашем случае заявке отказывается в обслуживании, если все 2 канала заняты, и очередь максимально заполнена (т.е. 4 человек в очереди). Для нахождения вероятности отказа разделим время пребывания СМО в состоянии с очередью 4 на общее время работы системы.</p>
                    <p><img width="167" height="51" src="http://www.bestreferat.ru/images/paper/15/38/7393815.png" alt=""></p>
                    <p>2) Относительная пропускная способность – это средняя доля поступивших заявок, обслуживаемых системой.</p>
                    <p><img width="78" height="25" src="http://www.bestreferat.ru/images/paper/16/38/7393816.png" alt=""></p>
                    <p>3) Абсолютная пропускная способность– это среднее число заявок, обслуживаемых в единицу времени.</p>
                    <p><img width="63" height="22" src="http://www.bestreferat.ru/images/paper/17/38/7393817.png" alt=""></p>
                    <p>4) Длина очереди, т.е. среднее число заявок в очереди. Длина очереди равна сумме произведений числа человек в очереди на вероятность соответствующего состояния. Вероятности состояний найдем как отношение времени нахождения СМО в этом состоянии к общему времени работы системы.</p>
                    <p><img width="558" height="42" src="http://www.bestreferat.ru/images/paper/18/38/7393818.png" alt=""></p>
                    <p>5) Среднее время пребывания заявки в очереди определяется формулой Литтла</p>
                    <p><img width="62" height="44" src="http://www.bestreferat.ru/images/paper/19/38/7393819.png" alt=""></p>
                    <p>6) Среднее число занятых каналовопределяется следующим образом:</p>
                    <p><img width="92" height="46" src="http://www.bestreferat.ru/images/paper/20/38/7393820.png" alt=""></p>
                    <p>7) Процент заявок, которым было отказано в обслуживании, находится по формуле</p>
                    <p><img width="413" height="54" src="http://www.bestreferat.ru/images/paper/21/38/7393821.png" alt=""></p>
                    <p>8) Процент обслуженных заявок находится по формуле</p>
                    <p><img width="416" height="54" src="http://www.bestreferat.ru/images/paper/22/38/7393822.png" alt=""></p>
                    <p>Т.к. показатели эффективности получаются в результате моделирования СМО в течение конечного времени, они содержат случайную компоненту. Поэтому, для получения более надежных результатов нужно провести их статистическую обработку. С этой целью оценим доверительный интервал для них по результатам 20 прогонов программы.</p>
                    <p>Величина<img width="16" height="17" src="http://www.bestreferat.ru/images/paper/23/38/7393823.png" alt="">попадает в доверительный интервал, если выполняется неравенство</p>
                    <p><img width="201" height="52" src="http://www.bestreferat.ru/images/paper/24/38/7393824.png" alt="">, где</p>
                    <p><img width="30" height="27" src="http://www.bestreferat.ru/images/paper/25/38/7393825.png" alt="">математическое ожидание (среднее значение), находится по формуле</p>
                    <p><img width="94" height="54" src="http://www.bestreferat.ru/images/paper/26/38/7393826.png" alt="">,</p>
                    <p><img width="31" height="22" src="http://www.bestreferat.ru/images/paper/27/38/7393827.png" alt="">исправленная дисперсия,</p>
                    <p><img width="173" height="54" src="http://www.bestreferat.ru/images/paper/28/38/7393828.png" alt="">,</p>
                    <p><i>N</i><i>=20</i>– число прогонов,</p>
                    <p><img width="13" height="17" src="http://www.bestreferat.ru/images/paper/29/38/7393829.png" alt="">– надежность. При<img width="59" height="21" src="http://www.bestreferat.ru/images/paper/30/38/7393830.png" alt="">и<i>N</i><i>=20</i><img width="220" height="26" src="http://www.bestreferat.ru/images/paper/31/38/7393831.png" alt="">.</p>
                    <p>

                    </p>
                    <div class="col-xs-12 text-center">
                        <p><label for="nBig">Число рабочих часов</label></p>
                        <p><input id="nBig" name="nBig" value="20" type="text"></p>
                        <p><label for="nSmall">Кол-во каналов</label></p>
                        <p><input id="nSmall" value="2" name="nSmall" type="text"></p>
                        <p><label for="queueMax">Макс. длина очереди</label></p>
                        <p><input id="queueMax" value="4" name="queueMax" type="text"></p>
                        <p><label for="lambda">Средняя интенсивность заявок</label></p>
                        <p><input id="lambda" value="4.8" name="lambda" type="text"></p>
                        <p><label for="nu">Поток обслуживаемых заявок</label></p>
                        <p><input id="nu" value="2" name="nu" type="text"></p>
                    </div>
                    <p></p>


                    <div class="col-xs-12 text-center"><button type="submit" id="rasschet" class="btn btn-primary">РАССЧЕТ!</button></div>
                </div>
            </div>
        </div>
    </div>

    <div class="fh5co-intro text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h1 class="intro-lead">Результаты</h1>
                    <p class="">Полученные при таких исходных данных результаты модели</p>
                </div>
                <div class="col-xs-12 text-center">
                    <div id="results">

                    </div>
                </div>

            </div>
        </div>
    </div>




</div>



<?php require_once('template_footer.php');?>
