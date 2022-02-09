<?php     
    $data = [];

    if( !empty( $_POST['website_urls'] ) ){
        
        $entered_url = $_POST['website_urls'];
        $url = [];
        
        //Storing each URL in new array element -- checking line break for exploding
        $entered_url = explode(PHP_EOL, $entered_url);
        
        //Removing empty elements from $entered_url array        
        for($i = 0; $i < count($entered_url); $i++){
            $tempUrl = trim($entered_url[$i], " \n\r\t\v\x00");
            if(empty($tempUrl) == false){
                $url[] = $entered_url[$i];
            }
        }
        
        if(!empty($url)){

            //Funtion to validate the url
            function makeValidUrl($url){
                //Check if the URL has Protocol (https or http) added or not and if not found, then add https to it
                $url = (preg_match("/https:\/\/|http:\/\//", $url) != false)? $url: "https://".$url;
                // Remove all illegal characters from a url
                $url = filter_var($url, FILTER_SANITIZE_URL);
                return $url;
            }

            //Processes the returned data by get_headers() and checks if there are any redirections (if yes, how many) and then retuns the data
            function processData($unprocessedData){
                /*
                    - Fetch Response, Message, Location (if exists), Protocol          
                    - Returns processed data
                */ 
                $processedData = [];
                $responseAndMessage = [];
                $location = [];
            
                //**** Getting Location (of redirection), Response, Message, and Protocol Starts ****//
                //Loop to get the Location if redirection is present
                for ($i=0; $i < count($unprocessedData); $i++) {    //Loop to target the data for each entered URL
                    foreach($unprocessedData[$i] as $key => $value){
                        if($key === 0){
                            $responseAndMessage = preg_split("/ /", $value, 3);
                            $responseAndMessage = [
                                'response' => $responseAndMessage[1],
                                'message' => ($responseAndMessage[2] == 'Found')? 'Temporarily Moved': $responseAndMessage[2]     //If message is 'Found' (302 redirection), then change the message
                            ];
                            unset($unprocessedData[$i][0]);
                        }
                        elseif(isset($unprocessedData[$i]['Location']) && $key === 'Location'){
                            $location = ['Location' => $value];
                            unset($unprocessedData[$i]['Location']);
                        }
                        unset($unprocessedData[$i]['Content-Length']);
                    }
                    //Getting Location on the first position
                    $processedData[$i] = $location + $responseAndMessage + $unprocessedData[$i];
                }
                return $processedData;
            }
            
            //Gets all the headers data and modified the data for printing the output
            function getData($url){

                $structuredData = [];
                $j = 0;   
                
                // '@' disables the warnings  retured by the function (if any) to be disaplyed on the frontend
                //Getting data in indexed array format
                $data = @get_headers($url);   

                if($data === false){
                    return false;
                }

                //Loop through each array element to see if ': ' is present. If yes, then it is part of the same URL data
                //Else store in a new array index (New URL data)
                //Also, if ': ' is found, then extracting the key from the element for a new associative array 
                for($i=0; $i < count($data); $i++){

                    if(strpos($data[$i], ': ') == false){
                        $j = ($i > 0)? ++$j: $j;
                        $structuredData[$j] = [$data[$i]];
                    }
                    else{
                        $key = preg_split('/: /', $data[$i], 2);
                        $structuredData[$j][$key[0]] = str_replace( ($key[0] . ": " ) , '', $data[$i]);
                    }

                }
                
                return processData($structuredData);
            }

            //Returns color based on response code
            function getColor($response){
                $color = '';
                switch($response[0]){
                    case 2: $color = "#22af22"; break;
                    case 3: $color = "#4d80f3"; break;
                    case 4: $color = "#cb9124"; break;
                    case 5: $color = "#d93232"; break;
                }
                return $color;
            }

            //Run validation for each URL
            for($i=0; $i < count($url); $i++){
                $filtered_url[] = makeValidUrl( $url[$i] );
            }

            //Getting data for each URL
            if( isset($filtered_url) && is_array($filtered_url) ){
                for($i=0; $i < count($filtered_url); $i++){
                    $current_url_data = getData($filtered_url[$i]);
                    //If current URL has data, then store it otherwise store Invalid URI error message
                    $current_url_data = ($current_url_data !== false)? $current_url_data: [
                                                                            [
                                                                                'Entered URL' => 'Invalid URL', 
                                                                                'response' => '400',
                                                                                'message' => 'Bad Request'
                                                                            ]
                                                                        ];

                    $data[] = ['URL' => $filtered_url[$i]] + $current_url_data;
                }
            }
        }        

    }