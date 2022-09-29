<?php
include("../../includes/config.php");

if(isset($_POST)){

    $filename = $_FILES["file"]["name"];
    $filenameTemp = $_FILES["file"]["tmp_name"];
    $ext = substr($filename,strrpos($filename,"."),(strlen($filename)-strrpos($filename,".")));
     
    //echo $ext;
    //exit;


    //we check,file must be have csv extention
    if($ext == ".csv"){
      $file = fopen($filenameTemp, "r");

      d($_FILES["file"]);

      var_dump($filename);

        var_dump($file);

        /*error_reporting(E_ALL);
        ini_set('display_errors', true);
        echo 'phpversion: ', phpversion(), "\n";

        $fp = fopen($file, 'a+');
        if ( !$fp ) {
          echo 'last error: ';
          var_dump(error_get_last());
        }
        else {
          echo "ok.\n";
        }
        */

         while(($emapData = fgetcsv($file, 10000, ";")) !== false){


            // TO DO: Check if product exist

            // TO DO: If exist then update else insert

            if($emapData[9] != 'Kategori'){

                $categoryExplode = explode('.',$emapData[9]);
                $category = '';

                foreach($categoryExplode as $categoryFraction){
                    $categoryFractionTrim = ltrim($categoryFraction, '0');
                    $category .= $categoryFractionTrim . '.';
                }

                $category = substr($category,0,-1);

                $slug = slugify($emapData[1]) . '-' .$emapData[0];

                $ean = sprintf("%.0f ",$emapData[4]);


                $db->where('Slug',$emapData[0]);
                $check = $db->has('products');

                $data = array(
                    "Slug"          => $slug,
                    "ArtNo"         => $emapData[0],
                    "SKU"           => $emapData[0],
                    "Name"          => $emapData[1],
                    "Description_EN"    => $emapData[1],
                    "Description_DA"    => $emapData[2],
                    "Description_DE"    => $emapData[3],
                    "EANCode"           => $ean,
                    "ColliSize"     => $emapData[5],
                    "Pallet120"     => $emapData[6],
                    "Pallet240"     => $emapData[7],
                    "HxBxD"         => $emapData[8],
                    "Category"      => $category,
                    "Visibility"    => $emapData[10]
                );

                if($check == true){

                    // Update
                    $db->where('Slug',$emapData[0]);
                    $db->update("products",$data);

                }else{

                    // Insert
                    $db->insert("products",$data);

                }
                

                d($data);

                //$sql = "INSERT into tableName(name,email,address) values('$emapData[0]','$emapData[1]','$emapData[2]')";
                //mysqli_query($con, $sql);

            }

         }



         fclose($file);
         echo "CSV File has been successfully Imported.";

    }else{
        echo "Error: Please Upload only CSV File";
    }

}

?>