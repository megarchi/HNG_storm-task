<?php

$json_request = $_SERVER["QUERY_STRING"] ?? '';
// $file = 'echo.php';

// if (file_exists($file)) {
//     $file = dirname(__DIR__) . '/test/' . $file;
//     shell_exec("node " . $file);
// }

// $output = shell_exec('ls');

// $output = explode(" ", $output);

// var_dump($output);
// $file = dirname(__DIR__) . '/test/' . $file;

// include 'script.js';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// $read = exec("node script.js");

// echo $read;
// $log_directory = dirname(__FILE__);
// $files = [];

// foreach (glob($log_directory . '/*.*') as $file) {
//     $files[] = $file;
// }

// foreach ($files as $file) {
//     $ext = pathinfo($file, PATHINFO_EXTENSION);
//     if ($ext === 'js') {
//         $read = exec("node {$file}");
//         echo $read . "\n";
//     }
// }


// code start here

// Get scripts (from yanmifeakeju)
$files = scandir('scripts');
$content = [];
$testPassed = 0;
$testFailed = 0;
$htmlOutput = [];


function getScripts($files)
{
    // add extensions here
    $extensions = [
        'js' => 'node',
        'php' => 'php',
        'py' => 'python',
        'javac' => 'java',
        'dart' => 'dart',
    ];    
    
    foreach ($files as $file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        // var_dump($ext);
        if (array_key_exists($ext, $extensions)) {
            $scripts[] = [ 
                'name' => 'scripts/' . $file, 
                'command' => $extensions[$ext], 
                'filename' => $file,
        ];  
     }
    }   
    
    return $scripts;

};
    
    $scripts = getScripts($files);

   
    // format output data
    
    foreach ($scripts as $key => $script) {
    if (file_exists($scripts[$key]['name'])) {
        $read = exec("{$scripts[$key]['command']} {$scripts[$key]['name']}");
        $string_array = explode(" ", $read);
        // print_r($string_array);
        
        // get values from string array
        $id = isset($string_array[9]) ? $string_array[9] : '';
        $name =  isset($string_array[4]) && isset($string_array[5]) ? $string_array[4].' '.$string_array[5] : '';
        $email = isset($string_array[12]) ? $string_array[12]  :'';
        $language = isset($string_array[14]) ? $string_array[14] : '';

        $content[] = [
            'file' => $scripts[$key]['filename'],
            'output' => $read,    
            "name"  => $name,    
            'id' => $id,
            'email' => $email,
            'status' => testStringContentsMatch($read),
            'language' => $language,

        ];

        $htmlOutput[] = [$read, testStringContentsMatch($read), $name];
    }}


     function testStringContentsMatch($string){
        if(preg_match('/^Hello\sWorld[,|.|!]?\sthis\sis\s[a-zA-Z]{2,}\s[a-zA-Z]{2,}\swith\sHNGi7\sID\s(HNG-\d{3,})\sand\semail\s([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})\susing\s[a-zA-Z|#]{2,}\sfor\sstage\s2\stask.?$/i', trim($string))){
            return 'Pass';
        }

        return 'Fail';
    }


foreach ($htmlOutput as $test) {
    if ($test[1] == 'Pass') {
        $testPassed++;
    } elseif ($test[1] == 'Fail') {
        $testFailed++;
    }
}

    
   

//    echo $testFailed.''.$testPassed;

?>
<?php  if(isset($json_request) && $json_request == 'json'){ 
    //return the json response 
        header('Content-Type: application/json'); 
        echo json_encode($content, true);

        ob_flush();
	    flush();
         }else {
    ?>

<!-- FRONTEND CODE HERE -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Team storm</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet" />
    <style>
    table,
    .container {
        margin: 20px auto;
        max-width: 1000px;
    }

    .stat {
        color: white;
    }

    .team-name {
        font-size: 1.6rem;
        margin-bottom: 10px;
    }

    .leaders {
        display: flex;
        justify-content: space-between;
    }

    .leaders li a {
        text-decoration: underline;
    }

    body {
        font-weight: bold;
        padding: 1em;
    }

    .stat {
        display: flex;
        justify-content: space-between;
    }

    thead {
        color: #fff;
    }

    tbody {
        text-align: center;
        color: #fff;
    }

    table {
        margin-top: 100px;
    }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="team-name">TEAM STORM</div>
            <ul class="leaders">
                <li>Leads:</li>
                <li>
                    <a href="https://www.github.com/Seymaster">ESI - Backend</a>
                </li>
                <li>
                    <a href="https://www.github.com/joshuafolorunsho">__Josh__ - Frontend</a>
                </li>
                <li>
                    <a href="https://www.github.com/">Aj - devOps</a>
                </li>
            </ul>
        </div>
    </header>

    <div>
        <div class="container">
            <div class="stat">
                <p class="bg-green-500 px-2 py-3">Total Submission: <?php echo ($testFailed + $testPassed); ?></p>
                <p class="bg-green-500 px-2 py-3">Passed: <?php echo $testPassed ?></p>
                <p class="bg-red-500 px-2 py-3">Failed: <?php echo $testFailed ?></p>
            </div>
        </div>
    </div>

    <table>
        <thead class="bg-blue-700">
            <tr>
                <th class="w-1/6 px-4 py-2">ID</th>
                <th class="w-1/6 px-4 py-2">Author</th>
                <th class="w-1/2 px-4 py-2">Message</th>
                <th class="w-1/6 px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 

                // counting rows
                $rows = 0;
                foreach($htmlOutput as $test){
                    $status =true;
                    if($status){
                        echo <<<EOL
                        <tr class="bg-green-500">
                            <td class="border px-4 py-2">$rows</td>
                            <td class="border px-4 py-2">$test[2]</td>
                            <td class="border px-4 py-2">$test[0]</td>
                            <td class="border px-4 py-2">Passed</td>
                        </tr>
                        EOL;   
                    }else {
                       echo  <<<EOL 
                       <tr class="bg-red-500">
                            <td class="border px-4 py-2">$rows</td>
                            <td class="border px-4 py-2">$test[2]</td>
                            <td class="border px-4 py-2">$test[0]</td>
                            <td class="border px-4 py-2">Failed</td>
                        </tr>
                        EOL;
                    }

                    $rows++
                        
                }
            ?>


        </tbody>
    </table>
</body>

</html>

<?php } ?>