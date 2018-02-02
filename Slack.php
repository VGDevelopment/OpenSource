<?php

namespace OpenSource; // replace your namespace here please

/*
This was coded by Lewis Brindley under the VGDevelopment Organisation.
By the MIT License, you can do whatever you want with this file with no restrictions unless implied in the License.
You cannot however remove this commented in citation of the authorship of the file. You must add this to any file using code from this file.
*/

class Slack {
    
    const URL = "---"; // Replace --- with your URL
    const HEADER = "Content-Type: application/x-www-form-urlencoded";
    const DEFAULT_CHANNEL = "bot"; // Replace "bot" with the channel you authenticated the webhook URL for
    
    public static function convertStringToSlackJSON(string $string): string {
        $replace = [
            '"' => "\""   
        ];
        $slackjson = strtr($string, $replace);
        return $slackjson;
    }
    
    public static function sendTextMessage(string $text, $channel = self::DEFAULT_CHANNEL): bool { // you can also send to different channels. Replace the $channel param in running method with the channel name string
        $post = '"text": "' . $text . '"';
        $string = self::convertStringToSlackJSON($post);
        $config = self::makeConfig($string, $channel);
        return self::sendCURLRequest($config);
    }
    
    public static function makeConfig(string $post, string $channel): array {
        return [
            "URL" => self::URL,
            "Header" => self::HEADER,
            "Channel" => self::convertStringToSlackJSON('"channel": "' . $channel . '"'),
            "Post" => $post
        ];
    }
    
    public static function sendCURLRequest(array $config): bool {
        $format = self::formatEOL([$config["Post"], "---", $config["Channel"]]);
        $package = self::makeJSONPackage($format);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $config["URL"]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $package);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [$config["Header"]]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        $r = curl_exec($curl);
        if (curl_errno($curl)) {
            var_dump("Error:" . curl_error($curl) . "\n");
        }
        curl_close($curl);
        if ($r === "ok") {
            return true;
        }
        return false;
    }
    
    public static function makeJSONPackage(string $convert): string {
        return "{
                    " . $convert .
                "}";
    }
    
    public static function formatEOL(array $los): string {
        $string = implode($los);
        $eol = [
            "---" => ",
            "     
        ];
        return strtr($string, $eol);
    }
    
}
