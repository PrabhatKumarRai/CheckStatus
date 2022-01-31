<?php 

    if( !empty( $_POST['website_urls'] ) ){

        //Storing each URL in new array element -- checking line break for exploding
        $url = explode(PHP_EOL, $_POST['website_urls']);

        //Funtion to validate the url
        function makeValidUrl($url){
            //Check if the URL has Protocol (https or http) added or not and if not found, then add https to it
            $url = (preg_match("/https:\/\/|http:\/\//", $url) != false)? $url: "https://".$url;
            // Remove all illegal characters from a url
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // Validate the URL
            return (filter_var($url, FILTER_VALIDATE_URL))? $url: false;
        }

        //Run validation for each URL
        for($i=0; $i < count($url); $i++){
            $temp = makeValidUrl( $url[$i] );
            if($temp != false){
                $filtered_url[] = $temp;
            }
        }

        //Gets all the headers data and modified the data for printing the output
        function getData($url){

            $data = @get_headers($url, 1);  // '@' disables the warnings  retured by the function to be disaplyed on the frontend

            //If a warning is detected and no data is returned by the function i.e, if the URL is not found, then as the function ran properly, it only retuns true
            // Otherwise it will return an array of data
            if( is_bool($data) !== true ){
                //getting protocol
                if(!empty( $data['X-Pingback'] )){
                    $temp = preg_split('/:/', 
                                (is_array($data['X-Pingback'] !== false)? $data['X-Pingback'][0]: $data['X-Pingback']), 
                            2); 
                    
                    $data = (!empty($temp[0]))? ['protocol' => strtoupper($temp[0])] + $data: $data;
                }

                //getting response and message and changing the location of redirection location url
                $temp = preg_split('/ /', $data[0], 3); 
                $data = ['response' => $temp[1]] + ['message' => $temp[2]] + $data; 

                //Unsetting/removing unwanted data
                unset($data[0], $data[1], $data['content-length'], $data['Set-Cookie']);

                return $data;
            }
            
            //Invalid URI
            return false; 

        }

        //Getting data for each URL
        if( is_array($filtered_url) ){
            for($i=0; $i < count($filtered_url); $i++){
                $current_url_data = getData($filtered_url[$i]);
                //If current URL has data, then store it otherwise store Invalid URI error message
                $current_url_data = ($current_url_data !== false)? $current_url_data: [
                                                                        'Entered URL' => 'Invalid URI', 
                                                                        'response' => '400',
                                                                        'message' => 'Bad Request'
                                                                    ];

                $data[] = ['URL' => $filtered_url[$i]] + $current_url_data;
            }
        }

    }

?>