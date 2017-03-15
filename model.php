<?php
/**
 * Created by PhpStorm.
 * User: MeGa
 * Date: 21.12.2016
 * Time: 0:32
 */
$nBig = $_POST['nBig']; //макс время работы системы
$nSmall = $_POST['nSmall']; // кол-во каналов
$queueMax = $_POST['queueMax']; // макс длина очереди
$lambda = $_POST['lambda']; // средняя интенсивность заявок
$nu = $_POST['nu']; // поток обслуживания заявок

//$nBig = 20; //число повторов, макс время работы системы
//$nSmall = 2; // кол-во каналов
//$queueMax = 4; // макс длина очереди
//$lambda = 4.8; // средняя интенсивность заявок
//$nu = 2; // поток обслуживания заявок


$timeStep = 0.001; // шаг времени
$timeOfFinishProcessingReq = []; // Время окончания обслуживания заявки во всех каналах
$timeInQueue = []; // Время пребывания СМО в состояниях с очередью
$processingTime = 0; // Время работы системы
$totalProcessingTime = 0; // Суммарное время обслуживания заявок
$requestEntryCount = 0; // Число поступивших заявок
$declinedRequestCount = 0; // Число отказанных заявок
$acceptedRequestCount = 0; // Число обслуженных заявок
$queueLength = 0; // Длина очереди
$sysCondition = 0; // состояние системы ( от 0 до $queueMax + $nSmall) 0 - система свободна, $nSmall - кассы заняты, начинается очередь, $queueMax + $nSmall - система полна, следующаая заявка - отказ

function isRequested($timeStep, $lambda) { // поступление заявки в СМО
    $r = mt_rand() / mt_getrandmax(); // случайное число
    if ($r<($timeStep * $lambda)) {
        return true;
    }
    return false;
}

function getServiceTime($nu) { //Время обслуживания заявки в системе
    $r = mt_rand() / mt_getrandmax(); // случайное число
    return (-1 / ($nu)  * log(1-$r));
}

function getCondition($nSmall, $timeOfFinishProcessingReq, $queueLength ) { // определяет текущее состояние системы
//    $p_currentCondit =0;
    $busyChannelCount = 0;
    for ($i = 0; $i<$nSmall; $i++) {
        if ($timeOfFinishProcessingReq[$i] > 0) {
            $k = 1;
            $busyChannelCount++;
        }
        else {
            $k = 0;
        }
//        $p_currentCondit += $k * ($i + 1);
    }
//    if ($busyChannelCount > 1) {
//        $p_currentCondit ++;
//    }
    return $busyChannelCount + $queueLength;
}

do {
    $processingTime += $timeStep;

    $sysCondition = getCondition($nSmall, $timeOfFinishProcessingReq, $queueLength);
if ($queueLength > 0) {

    for ($i= 0; $i<=$queueLength; $i++) { // Изменение времени пребывания СМО в состояниях очереди

            $timeInQueue[$i] += $timeStep;

    }

    for ($i= 0; $i<$nSmall; $i++) {
        if ($timeOfFinishProcessingReq[$i] <= 0) {// помещение заявки из очереди на свободный канал
            $timeOfFinishProcessingReq[$i] = getServiceTime($nu);
            $totalProcessingTime+= $timeOfFinishProcessingReq[$i];
            $queueLength --;
        }
    }
}


    if (isRequested($timeStep, $lambda)) {
        $requestEntryCount ++;
        if ($queueLength < $queueMax) {
            $acceptedRequestCount ++;
            if ($sysCondition < $nSmall) { // помещение заявки без очереди в кассу
                $timeOfFinishProcessingReq[$sysCondition] = getServiceTime($nu);
                $totalProcessingTime+= $timeOfFinishProcessingReq[$sysCondition];
            }
            else {
                $queueLength ++ ; // помещаем заявку в очередь
            }
        }
        else {
            $declinedRequestCount ++;
        }
    }

    for ($i = 0; $i < $nSmall; $i++) { // обслуживание
        if ($timeOfFinishProcessingReq[$i] > 0) {
            $timeOfFinishProcessingReq[$i] -= $timeStep;
        }
    }

}
while (
    $processingTime < $nBig
);

//var_dump('Число поступивших заявок', $requestEntryCount, 'Число принятых заявок' , $acceptedRequestCount, 'Число отмененных заказов(отказов)' ,$declinedRequestCount);

$otkazVer = $timeInQueue[$queueMax]/$processingTime;
//var_dump('Вероятность отказа в обслуживании:' ,$otkazVer);

$Q = 1 - $otkazVer;
//var_dump('Относительная пропускная способность', $Q);

$A = $lambda * $Q;
//var_dump('Абсолютная пропускная способность', $A);
$L0 = 0;
for ($i = 1; $i <= $queueMax; $i++ ) {
    $L0 += $i * ($timeInQueue[$i+$nSmall]) / $processingTime;
}
//var_dump('Среднее число заявок в очереди', $L0);

$T0 = $L0 / $lambda;
//var_dump('Среднее время пребывания заявки в очереди', $T0);

$k = $A / $queueMax;
//var_dump('Среднее число занятых каналов', $k);

$declinedPercend = $declinedRequestCount / $requestEntryCount * 100;
//var_dump('Процент заявок, которым было отказано в обслуживании', $declinedPercend);
$servedPercent = $acceptedRequestCount/$requestEntryCount*100;
//var_dump('Процент обслуженных заявок', $servedPercent);

$output = array(
        'Число поступивших заявок' =>$requestEntryCount,
        'Число принятых заявок' => $acceptedRequestCount,
        'Число отмененных заказов(отказов)' => $declinedRequestCount,
        'Вероятность отказа в обслуживании' => $otkazVer,
        'Относительная пропускная способность' => $Q,
        'Абсолютная пропускная способность' => $A,
        'Среднее число заявок в очереди' => $L0,
        'Среднее время пребывания заявки в очереди' => $T0,
        'Среднее число занятых каналов' => $k,
        'Процент заявок, которым было отказано в обслуживании' => $declinedPercend,
        'Процент обслуженных заявок' => $servedPercent
);
$html= '';
foreach ($output as $key=>$value) {
    $html.= "<p><b>$key:</b>  = $value</p>";
}
//echo json_encode($output);
echo $html;