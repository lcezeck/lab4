<?php

$arr;
$start;
$finish;
$minLength = 0;
$shortestPath = array();


function validatearr(& $arr)
{
    for ($i = 0; $i < count($arr); $i++)
    {
        $arr[$i] = array_diff($arr[$i], array(""));
        if (count($arr[$i]) == 0)
        {
            unset($arr[$i]);
            $arr = array_values($arr);
            $i--;
        }
    }

    if (count($arr) == 0)
    {
        echo "Пропущено поле ввода!";
        exit;
    }
    
    for ($i = 0; $i < count($arr); $i++)
    {
        if (count($arr) != 4)
        {
            die ("Введенная матрица не размерности 4 на 4");          
        }
    } 

    for ($i = 0; $i < count($arr); $i++)
    {
        for ($j = 0; $j < count($arr[$i]); $j++)
        {
            if ($arr[$i][$j] != "-")
            {
                for ($k = 0; $k < mb_strlen($arr[$i][$j]); $k++)
                {
                    if ($arr[$i][$j][$k] < "0" || $arr[$i][$j][$k] > "9")
                    {
                        echo "Неправильный формат данных!";
                        exit;
                    }
                }
                $arr[$i][$j] = (int)$arr[$i][$j];
            }
        }
    }

    for ($i = 0; $i < count($arr); $i++)
    {
        for ($j = 0; $j < count($arr[$i]); $j++)
        {
            if ($i == $j)
            {
                if ($arr[$i][$j] !== 0)
                {
                    echo "Элемент, находящийся на главной диагонали, не равен нулю!";
                    exit;
                }
            }
            else
            {
                if ($arr[$i][$j] === 0)
                {
                    echo "Элемент, не находящийся на главной диагонали, равен нулю!";
                    exit;
                }
            }
        }
    }
}



function validatePoint(& $point, $arrSize)
{
    if ($point == "")
    {
        echo "Пропущено поле ввода!";
        exit;
    }
    
    for ($i = 0; $i < mb_strlen($point); $i++)
    {
        if ($point[$i] < "0" || $point[$i] > "9")
        {
            echo "Неправильный формат данных!";
            exit;
        }
    }
    
    $point = (int)$point - 1;   
    
    if ($point < 0 || $point >= $arrSize)
    {
        echo "Вершина не найдена!";
        exit;
    }    
}


function findShortestPath($currentPosition, $length, $path)
{
    global $finish;
    global $minLength;    
    
    if ($currentPosition == $finish)
    {      
        if ($minLength == 0 || $length < $minLength)
        {
            global $shortestPath;
            
            $minLength = $length;
            if ($length == 0)
            {
                $path[] = $currentPosition;
            }
            $shortestPath = $path;
        }        
    }
    else
    {
        global $arr;

        for ($i = 0; $i < count($arr[$currentPosition]); $i++)
        {
            if ($arr[$currentPosition][$i] !== 0 && $arr[$currentPosition][$i] !== "-")
            {
                if ($minLength == 0 || $length + $arr[$currentPosition][$i] < $minLength)
                {
                    if (!in_array($i, $path))
                    {
                        if ($path[count($path) - 1] == $currentPosition)
                        {
                            $path[] = $i;
                        }
                        else
                        {
                            $path[count($path) - 1] = $i;
                        }
                        findShortestPath($i, $length + $arr[$currentPosition][$i], $path);
                    }                    
                }
            }
        }
    }
}


$arr = explode(PHP_EOL, $_POST["arr"]);
for ($i = 0; $i < count($arr); $i++)
{
    $arr[$i] = explode(" ", $arr[$i]);
    
}

$start = $_POST["start"];
$finish = $_POST["finish"];

validatearr($arr);
validatePoint($start, count($arr));
validatePoint($finish, count($arr));

findShortestPath($start, 0, $path = array($start));

if ($minLength == 0 && $start != $finish)
{
    echo "Путь из вершины " . ($start + 1) . " в вершину " . ($finish + 1) . " не найден.";
}
else
{
    echo "Длина кратчайшего пути из вершины " . ($start + 1) . " в вершину " . ($finish + 1) . " равна " . $minLength . "<br>";
    echo "Кратчайший маршрут ";
    for ($i = 0; $i < count($shortestPath) - 1; $i++)
    {
        echo ($shortestPath[$i] + 1) . " => "; 
    }
    echo $shortestPath[count($shortestPath) - 1] + 1;
}

?>