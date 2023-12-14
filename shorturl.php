<?php
// ShortUrl extension, https://github.com/pftnhr/yellow-shorturl

class YellowShorturl {
    const VERSION = "0.8.19";
    public $yellow;         // access to API
    
    public function onLoad($yellow) {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault("ShorturlApi", "<your host>/yourls-api.php");
        $this->yellow->system->setDefault("ShorturlSecret", "secret signature token from <your host>/admin/tools.php");
    }
    
    public function onParseContentShortcut($page, $name, $text, $type) {
        $output = null;
        if ($name=="shorturl" && ($type=="block" || $type=="inline")) {
            list($shorturlLong, $shorturlKeyword, $shorturlTitle) = $this->yellow->toolbox->getTextArguments($text);
            $shorturlLong    = $this->yellow->lookup->normaliseUrl(
                                    $this->yellow->system->get("coreServerScheme"),
                                    $this->yellow->system->get("coreServerAddress"),
                                    $this->yellow->system->get("coreServerBase"),
                                    $page->location);
            $shorturlKeyword = $page->get("keyword");
            $shorturlTitle   = $page->get("title");
            $shorturlApi     = $this->yellow->system->get("ShorturlApi");
            $shorturlSecret  = $this->yellow->system->get("ShorturlSecret");
            
            $output .= $this->shortenWithYourls($shorturlLong, $shorturlKeyword, $shorturlTitle, $shorturlApi, $shorturlSecret);
        }
        return $output;
    }
    
    public function shortenWithYourls($shorturlLong, $shorturlKeyword, $shorturlTitle, $shorturlApi, $shorturlSecret) {
       $shortUrl = null;
       /*
        * YOURLS : sample file showing how to use the API
        * This shows how to tap into your YOURLS install API from *ANOTHER* server
        * not from a file hosted on the same server. It's just a bit dumb to make a
        * remote HTTP request to the server the request originates from.
        *
        * Rename to .php
        *
        */
       
       // EDIT THIS: your auth parameters
       $signature = $shorturlSecret;
       
       // EDIT THIS: the query parameters
       $url     = $shorturlLong;            // URL to shrink
       $keyword = $shorturlKeyword;         // custom Keyword (optional)
       $title   = $shorturlTitle;           // title (optional)
       $format  = 'json';                   // output format: 'json', 'xml' or 'simple'
       
       // EDIT THIS: the URL of the API file
       $api_url = $shorturlApi;
       
       
       // Init the CURL session
       $ch = curl_init();
       curl_setopt( $ch, CURLOPT_URL, $api_url );
       curl_setopt( $ch, CURLOPT_HEADER, 0 );            // No header in the result
       curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Return, do not echo result
       curl_setopt( $ch, CURLOPT_POST, 1 );              // This is a POST request
       curl_setopt( $ch, CURLOPT_POSTFIELDS, array(      // Data to POST
               'url'      => $url,
               'keyword'  => $keyword,
               'title'  => $title,
               'format'   => $format,
               'action'   => 'shorturl',
               'signature' => $signature
           ) );
       
       // Fetch and return content
       $data = curl_exec($ch);
       curl_close($ch);
       
       $data = json_decode( $data );
       
       // Do something with the result. Here, we just echo it.
       if ($data == null) {
            $chGet = curl_init();
            curl_setopt( $chGet, CURLOPT_URL, $api_url );
            curl_setopt( $chGet, CURLOPT_HEADER, 0 );            // No header in the result
            curl_setopt( $chGet, CURLOPT_RETURNTRANSFER, true ); // Return, do not echo result
            curl_setopt( $chGet, CURLOPT_POST, 1 );              // This is a POST request
            curl_setopt( $chGet, CURLOPT_POSTFIELDS, array(      // Data to POST
                  'url'      => $url,
                  'keyword'  => $keyword,
                  'title'  => $title,
                  'format'   => $format,
                  'action'   => 'geturl',                       // For tbhis to work you need to have https://github.com/timcrockford/yourls-api-edit-url installed and activated
                  'signature' => $signature
              ) );
            
            // Fetch and return content
            $dataGet = curl_exec($chGet);
            curl_close($chGet);
            
            $dataGet = json_decode( $dataGet );
            $shortUrl = '<your YOURLS host>'.$dataGet->keyword;     // Replace "<your YOUR YOURLS host>" with the URL of your Yourls installation
            
       } else {
           
           $shortUrl = $data->shorturl;
       }
       
       return $shortUrl;
    }
}
