<?php
/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
/*
class CustomUploadHandler extends UploadHandler {

    public function post($print_response = true) {
        $result = parent::post(false);
        // Do something with $result['files']
        $array = json_decode(json_encode($result), True);


                                             //   var_dump($array);
                                              //  echo $array['name'];
    //    echo $array["files"][0]["name"];
     //
                                             //   $arr = [];
                                             //   foreach ($array as $pictureuploaded) {
                                                    # code...
                                              //  $arr[] = $pictureuploaded["name"];
                                             //       var_dump($array);
                                             //   }
                                             //   var_dump($arr);
                                        //        var_dump($array['files']);

                                               // echo $result['files'][0]['name'];
        return $this->generate_response(
            $result,
            $print_response
        );
    }

}
*/
//var_dump($_POST['productid']);
$upload_handler = new UploadHandler();

//$upload_handler = new CustomUploadHandler();
