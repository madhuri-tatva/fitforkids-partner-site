<?php

include("../../includes/config.php");
header('Content-type: text/plain; charset=utf-8');
// echo 'sdf';exit;
if(isset($_GET)){

    //All user measurements
    $userMeasurements = $db->get("user_measurement_details");    

    if(isset($userMeasurements) && !empty($userMeasurements)){
        $csvArray = array();
        foreach($userMeasurements as $kk => $measurements){
            $userMeasurementData = array();
            $userData = array();
            $measurementStr1 = $measurementStr2 = $measurementStr3 = $measurementStr4 = $measurementStr5 = '';

            if($measurements['set_measurements'] > 0){
                $db->where('Id', $measurements['user_id']);
                $userData = $db->getOne("users");
                
                if(isset($userData) && !empty($userData) && !empty($userData['Email'])){
                    $userMeasurementData['Firstname'] = $userData['Firstname'];
                    $userMeasurementData['Lastname'] = $userData['Lastname'];
                    $userMeasurementData['Email'] = $userData['Email'];
                


                //measurement1
                if(!empty($measurements['measurement_data_1'])){
                    $measurement1Ary = json_decode($measurements['measurement_data_1'], true);
                    foreach($measurement1Ary as $key => $measurement1){
                        $qusNum = 'Q'.$key;
                        if($measurementStr1 == ''){
                            $measurementStr1 .= $qusNum .': '.$measurement1;
                        }else{
                            $measurementStr1 .= ', '.$qusNum .': '.$measurement1;
                        }
                    }
                }

                //measurement 2
                if(!empty($measurements['measurement_data_2'])){
                    $measurement2Ary = json_decode($measurements['measurement_data_2'], true);
                    foreach($measurement2Ary as $key => $measurement2){
                        $qusNum = 'Q'.$key;
                        if($measurementStr2 == ''){
                            $measurementStr2 .= $qusNum .': '.$measurement2;
                        }else{
                            $measurementStr2 .= ', '.$qusNum .': '.$measurement2;
                        }
                    }
                }
                //measuremenr 3
                if(!empty($measurements['measurement_data_3'])){
                    $measurement3Ary = json_decode($measurements['measurement_data_3'], true);
                    foreach($measurement3Ary as $key => $measurement3){
                        $qusNum = 'Q'.$key;
                        if($measurementStr3 == ''){
                            $measurementStr3 .= $qusNum .': '.$measurement3;
                        }else{
                            $measurementStr3 .= ', '.$qusNum .': '.$measurement3;
                        }
                    }
                }

                //measuremenr 4
                if(!empty($measurements['measurement_data_4'])){
                    $ansStr = '';
                    $measurement4Ary = json_decode($measurements['measurement_data_4'], true);
                    foreach($measurement4Ary as $key => $measurement4){
                        $qusNum = 'Q'.$key;
                        if(is_array($measurement4)){
                            $ansStr = '[ ';
                            $measurementStr4 .= ', '.$qusNum. ': ';
                            foreach($measurement4 as $k => $value){
                                if($k == 1){
                                    $ansStr .= 'Q'.$k.': '.$value;
                                }else{
                                    $ansStr .= ', Q'.$k.': '.$value;
                                }
                            }
                            $ansStr .= ' ]';
                            $measurementStr4 = $measurementStr4.$ansStr;
                        }else{
                            if($measurementStr4 == ''){
                                $measurementStr4 .= $qusNum .': '.$measurement4;
                            }else{
                                $measurementStr4 .= ', '.$qusNum .': '.$measurement4;
                            }
                        }
                    }
                }

                //measuremenr 5
                if(!empty($measurements['measurement_data_5'])){
                    $ansStr = '';
                    $measurement5Ary = json_decode($measurements['measurement_data_5'], true);
                    foreach($measurement5Ary as $key => $measurement5){
                        $qusNum = 'Q'.$key;
                        if($key == 1   && is_array($measurement5)){
                            $ansStr = '[ ';
                            $measurementStr5 .= $qusNum. ': ';
                            foreach($measurement5 as $k => $value){
                                if($k == 1){
                                    $ansStr .= 'Q'.$k.': '.$value;
                                }else{
                                    $ansStr .= ', Q'.$k.': '.$value;
                                }
                            }
                            $ansStr .= ' ]';
                            $measurementStr5 = $measurementStr5.$ansStr;
                        }elseif($key == 2 && is_array($measurement5)){
                            $ansStr = '[ ';
                            $measurementStr5 .= ', '.$qusNum. ': ';
                            foreach($measurement5 as $k => $value){
                                if($k == 1){
                                    $ansStr .= 'Q'.$k.': '.$value.'%';
                                }else{
                                    $ansStr .= ', Q'.$k.': '.$value.'%';
                                }
                            }
                            $ansStr .= ' ]';
                            $measurementStr5 = $measurementStr5.$ansStr;
                        }else{
                            if($measurementStr5 == ''){
                                $measurementStr5 .= $qusNum .': '.$measurement5;
                            }else{
                                $measurementStr5 .= ', '.$qusNum .': '.$measurement5;
                            }
                        }
                    }
                }
            }
            if(!empty($measurementStr1)){
                $userMeasurementData['measurement_1'] = $measurementStr1;
                $userMeasurementData['measurement_2'] = $measurementStr2;
                $userMeasurementData['measurement_3'] = $measurementStr3;
                $userMeasurementData['measurement_4'] = $measurementStr4;
                $userMeasurementData['measurement_5'] = $measurementStr5;
                array_push($csvArray, $userMeasurementData);
            }
            
        }
    }
    
    }
       $arrayHeader = array(
            "Fornavn",
            "Efternavn",
            "E-mail",
            "Spørgeskema 1",
            "Spørgeskema 2",
            "Spørgeskema 3",
            "Spørgeskema 4",
            "Spørgeskema 5",
        );

        // USER ARRAY
        $arrayMeasurements = array();
        $arrayMeasurements[] = $arrayHeader;


        foreach($csvArray as $val){
            $arrayMeasurements[] = $val;
        }

        array_to_csv_download($arrayMeasurements);

}

?>