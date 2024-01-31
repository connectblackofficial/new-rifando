<?php

namespace App\Helpers;

use App\Models\Participant;
use App\Models\PixAccount;

class ManualPixGenerator
{
    private $participant;
    private $product;
    private $pixAccount;

    public function __construct(PixAccount $pixAccount, Participant $participant)
    {
        $this->pixAccount = $pixAccount;
        $this->participant = $participant;
        $this->product = $this->participant->product()->first();
    }


    public function getPix()
    {
        $px[00] = "01";
        $px[26][00] = "BR.GOV.BCB.PIX";
        $tipoChave = $this->pixAccount->key_type;
        $chavePix = $this->pixAccount->key_value;
        $name = $this->pixAccount->beneficiary_name;;
        if ($tipoChave != 'email' && $tipoChave != 'random') {
            $px[26][01] = preg_replace('/[^0-9.]+/', '', $chavePix);
            if ($tipoChave == 'phone') {
                $px[26][01] = '+55' . $px[26][01];
            }
        } else {
            $px[26][01] = $chavePix;
        }
        $px[26][02] = $this->participant->getDescription();
        $px[52] = "0000";
        $px[53] = "986";
        $px[54] = $this->participant->valor;
        $px[58] = "BR";
        if (empty($name)) {
            $px[59] = "NOME BENEFICIARIO";
        } else {
            $px[59] = strtoupper($this->removeAcentos($this->removeCharEspeciais($name)));
        }
        $px[60] = "SAO PAULO";
        $px[62][05] = $this->participant->id;

        $pix = $this->montaPix($px);
        $pix .= "6304";
        $pix .= $this->crcChecksum($pix);
        return ['mounted_pix' => $pix,'pixId'=>md5($pix), 'pix_key' => $px[26][01], 'pix_url' => 'https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=' . $pix];
    }

    private function montaPix($px)
    {
        $ret = "";
        foreach ($px as $k => $v) {
            if (!is_array($v)) {
                if ($k == 54) {
                    $v=number_format($v,2,'.','');
                } else {
                    $v = $this->removeCharEspeciais($v);
                }
                $ret .= $this->c2($k) . $this->cpm($v) . $v;
            } else {
                $conteudo = $this->montaPix($v);
                $ret .= $this->c2($k) . $this->cpm($conteudo) . $conteudo;
            }
        }
        return $ret;
    }

    private function removeCharEspeciais($txt)
    {
        return preg_replace('/\W /', '', $this->removeAcentos($txt));
    }

    private function removeAcentos($texto)
    {
        $search = explode(",", "à,á,â,ä,æ,ã,å,ā,ç,ć,č,è,é,ê,ë,ē,ė,ę,î,ï,í,ī,į,ì,ł,ñ,ń,ô,ö,ò,ó,œ,ø,ō,õ,ß,ś,š,û,ü,ù,ú,ū,ÿ,ž,ź,ż,À,Á,Â,Ä,Æ,Ã,Å,Ā,Ç,Ć,Č,È,É,Ê,Ë,Ē,Ė,Ę,Î,Ï,Í,Ī,Į,Ì,Ł,Ñ,Ń,Ô,Ö,Ò,Ó,Œ,Ø,Ō,Õ,Ś,Š,Û,Ü,Ù,Ú,Ū,Ÿ,Ž,Ź,Ż");
        $replace = explode(",", "a,a,a,a,a,a,a,a,c,c,c,e,e,e,e,e,e,e,i,i,i,i,i,i,l,n,n,o,o,o,o,o,o,o,o,s,s,s,u,u,u,u,u,y,z,z,z,A,A,A,A,A,A,A,A,C,C,C,E,E,E,E,E,E,E,I,I,I,I,I,I,L,N,N,O,O,O,O,O,O,O,O,S,S,U,U,U,U,U,Y,Z,Z,Z");
        return $this->removeEmoji(str_replace($search, $replace, $texto));
    }

    private function removeEmoji($string)
    {
        return preg_replace('%(?:
\xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
| \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
)%xs', '  ', $string);
    }

    private function cpm($tx)
    {
        if (strlen($tx) > 99) {
            throw new \Exception("Tamanho máximo deve ser 99, inválido: $tx possui " . strlen($tx) . " caracteres.");
        }
        return $this->c2(strlen($tx));
    }

    private function c2($input)
    {
        return str_pad($input, 2, "0", STR_PAD_LEFT);
    }

    private function crcChecksum($str)
    {
        $crc = 0xFFFF;
        $strlen = strlen($str);
        for ($c = 0; $c < $strlen; $c++) {
            $crc ^= $this->charCodeAt($str, $c) << 8;
            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc = $crc << 1;
                }
            }
        }
        $hex = $crc & 0xFFFF;
        $hex = dechex($hex);
        $hex = strtoupper($hex);
        $hex = str_pad($hex, 4, '0', STR_PAD_LEFT);
        return $hex;
    }

    private function charCodeAt($str, $i)
    {
        return ord(substr($str, $i, 1));
    }

}
