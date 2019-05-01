<?php
/**
 * Created by xiaoze <zeasly@live.com>.
 * User: ze
 * Date: 2018/8/11
 * Time: 下午3:52
 */

namespace App\Service;


class NumberGenerator
{
    /**
     * 生成订单号
     * @return string
     * @author 穆风杰<hcy.php@qq.com>
     */
    public static function orderNum()
    {
        $opt = [
            'number' => true,
            'lcase'  => false,
            'ucase'  => false,
            'length' => 6,
        ];
        return date('YmdHis') . static::getRandom($opt);
    }

    /**
     * 生成规则计算
     * @param array $data
     * @return string
     */
    public static function getRandom($data = [])
    {
        $opt = [
            'number' => true,
            'lcase'  => true,
            'ucase'  => true,
            'length' => 16,
        ];

        if (!empty($data)) {
            $opt = array_merge($opt, $data);
        }

        $origin_data = [];

        $number = [0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9];
        $lcase = [
            10 => 'a',
            11 => 'b',
            12 => 'c',
            13 => 'd',
            14 => 'e',
            15 => 'f',
            16 => 'g',
            17 => 'h',
            18 => 'i',
            19 => 'j',
            20 => 'k',
            21 => 'l',
            22 => 'm',
            23 => 'n',
            24 => 'o',
            25 => 'p',
            26 => 'q',
            27 => 'r',
            28 => 's',
            29 => 't',
            30 => 'u',
            31 => 'v',
            32 => 'w',
            33 => 'x',
            34 => 'y',
            35 => 'z',
        ];
        $ucase = [
            35 => 'A',
            36 => 'B',
            37 => 'C',
            38 => 'D',
            39 => 'E',
            40 => 'F',
            41 => 'G',
            42 => 'H',
            43 => 'I',
            44 => 'J',
            45 => 'K',
            46 => 'L',
            47 => 'M',
            48 => 'N',
            49 => 'O',
            50 => 'P',
            51 => 'Q',
            52 => 'R',
            53 => 'S',
            54 => 'T',
            55 => 'U',
            56 => 'V',
            57 => 'W',
            58 => 'X',
            59 => 'Y',
            60 => 'Z',
        ];

        if ($opt['number']) {
            if (empty($origin_data)) {
                $origin_data = $number;
            }
        }

        if ($opt['lcase']) {
            if (empty($origin_data)) {
                $origin_data = $lcase;
            } else {
                $origin_data = array_merge($origin_data, $lcase);
            }
        }

        if ($opt['ucase']) {
            if (empty($origin_data)) {
                $origin_data = $ucase;
            } else {
                $origin_data = array_merge($origin_data, $ucase);
            }
        }

        $result = '';
        for ($i = 0; $i < $opt['length']; $i++) {
            $result .= $origin_data[array_rand($origin_data, 1)];
        }

        return $result;
    }
}
