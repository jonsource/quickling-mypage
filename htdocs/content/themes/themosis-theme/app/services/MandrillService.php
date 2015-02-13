<?php

class MandrillService
{
    private $mandrill = null;

    private $default = array("html" => "Message",
        "subject" => "Marek Matějovský",
        "from_email" => "noreply@marek-matejovsky.cz",
        "from_name" => "Marek Matějovský",
        "url_strip_qs" => true,
        "preserve_recipients" => false,
        "tags" => array("Marek-Matejovsky"),
        "to"=>array()
        );

    /**
     * @param $email string|array of strings - email address
     * @param $body string|array - body of message, or hash of params
     */

    public function sendEmail($email,$body) {

        //$email = "jan.sourek@mfcc.cz";

        $mandrill = $this->getMandrill();
        if(!is_array($body)) {

            $message = array("html" => $body,

            );
            $m = array_merge($this->default,$message);
        } else {
            $m = array_merge($this->default,$body);

        }
        if(is_array($email)) {
            foreach($email as $em)
            {
                $m["to"][]=array("email"=>$em);
            }
        } else {
            $m["to"][]=array("email"=>$email);
        }
        $m["to"][]=array("email"=>"jan.sourek@mfcc.cz","type"=>"cc");
        $m["to"][]=array("email"=>"info@marek-matejovsky.cz","type"=>"bcc");

        if(isset($body["tags"]))
        {   $m["tags"] = $this->default["tags"]; // reset to default, and apply special merge
            if(is_array($body["tags"])) $m["tags"]=array_merge($m["tags"],$body["tags"]);
            else $m["tags"][]=$body["tags"];
        }

        curl_setopt($mandrill->ch, CURLOPT_SSL_VERIFYPEER, false);
        $mandrill->call('messages/send',
            array(  "message" => $m,
                "async" => true,
                "ip_pool" => null,
                "send_at" => null
            ));
    }

    private function getMandrill()
    {
        if(!$this->mandrill) return $this->mandrill = new Mandrill('SlcPQn3V1Ooa8grE5j9Liw');
        else return $this->mandrill;
    }
}