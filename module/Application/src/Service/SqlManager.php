<?php
namespace Application\Service;



class SqlManager 
{
    private $entityManager;
    protected $count_district = 12;
    protected $provinces = [
        ["p" => "Ha Noi City",
        "districts" => [
            "Hoan Kiem",
            "Ba Dinh",
            "Dong Da",
            "Hai Ba Trung"
            ],  
        ], 
        ["p" => "Ho Chi Minh City",
        "districts" => [
            "Quan 1",
            "Quan 2",
            "Quan 3",
            "Quan 4"
            ],  
        ],
        ["p" => "Da Nang City",
        "districts" => [
            "Quan 1",
            "Quan 2",
            "Quan 3",
            "Quan 4"
            ],  
        ],
    ];
    protected $address = [
        "21 Ha Long Street", 
        "45 Long Bien Street", 
        "37 Hoang Cau Street",
        "37 Lac Long Quan Street",
        "11 Le Hong Phong Street"
        ];
    protected $users = [
        ["admin@gmail.com","admin",1,"0912593240",1],
        ["admin1@gmail.com","admin1",1,"0912593240",2],
        ["truong@gmail.com","truong",0,"0912593240",3],
        ["duc@gmail.com","duc",0,"0912593240",4],
        ["long@gmail.com","long",0,"0912593240",5],
        ["ninh@gmail.com","ninh",0,"0912593240",6],
        ["test@gmail.com","test",0,"0912593240",7],
    ];
    protected $category = [
        [
            'p' => 'Man',
            'c' => [
                [ 
                    'p' => 'Jackets Man',
                    'c' => [
                        'Jackets Man 1',
                        'Jackets Man 2',
                        'Jackets Man 3'
                    ],
                ],
                [ 
                    'p' => 'Jean Man',
                    'c' => [
                        'Jean Man 1',
                        'Jean Man 2',
                        'Jean Man 3'
                    ],
                ],
                [ 
                    'p' => 'Suit Man',
                    'c' => [
                        'Suit Man 1',
                        'Suit Man 2',
                        'Suit Man 3'
                    ],
                ],
            ],
        ],
        [
            'p' => 'Woman',
            'c' => [
                [ 
                    'p' => 'Jackets Woman',
                    'c' => [
                        'Jackets Woman 1',
                        'Jackets Woman 2',
                        'Jackets Woman 3'
                    ],
                ],
                [ 
                    'p' => 'Jean Woman',
                    'c' => [
                        'Jean Woman 1',
                        'Jean Woman 2',
                        'Jean Woman 3'
                    ],
                ],
                [ 
                    'p' => 'Suit Woman',
                    'c' => [
                        'Suit Woman 1',
                        'Suit Woman 2',
                        'Suit Woman 3'
                    ],
                ],
            ],
        ],
    ];
    protected $products = [
        ['Vinyl Jacker', 200, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 6, '/img/products/image_10.jpg'],
        ['CHESTER COAT', 340, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 7, '/img/products/image_20.jpg'],
        ['BIKER JACKET', 530, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 8, '/img/products/image_30.jpg'],
        ['Fashion Suit', 120, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 9, '/img/products/image_40.jpg'],
        ['Fashion Shirt', 890, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 17, '/img/products/image_50.jpg'],
        ['DRESS WITH RUFFLE', 110, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 18, '/img/products/image_60.jpg'],

        ['Vinyl Jacker 1', 200, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 8, '/img/products/image_70.jpg'],
        ['CHESTER COAT 1', 340, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 6, '/img/products/image_80.jpg'],
        ['BIKER JACKET 1', 530, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 7, '/img/products/image_90.jpg'],
        ['Fashion Suit 1', 120, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 10, '/img/products/image_100.jpg'],
        ['Fashion Shirt 1', 890, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 19, '/img/products/image_110.jpg'],
        ['DRESS WITH RUFFLE 1', 110, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 20, '/img/products/image_120.jpg'],

        ['Vinyl Jacker 2', 200, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 7, '/img/products/image_130.jpg'],
        ['CHESTER COAT 2', 340, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 9, '/img/products/image_140.jpg'],
        ['BIKER JACKET 2', 530, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 6, '/img/products/image_150.jpg'],
        ['Fashion Suit 2', 120, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 8, '/img/products/image_160.jpg'],
        ['Fashion Shirt 2', 890, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 21, '/img/products/image_170.jpg'],
        ['DRESS WITH RUFFLE 2', 110, 'Straight fit, vinyl jacket with laminated effect. Features a classic collar and contrasting snap buttons for fastening', 22, '/img/products/image_180.jpg'],

    ];
    protected $views = [22,33,23,44,55,36,332,100,334,111,22,33,23,44,55,36,332,100,334,111];
    protected $arr = [0,1,1,2,3,2,2,1,1,2,3,2,2,1,1,2,3,2,2];
    protected $arr_color = [0,[0],[1],[1,4],[0,4,5],[0,3],[5,6],[0],[1],[1,4],[0,4,5],[0,3],[5,6],[0],[1],[1,4],[0,4,5],[0,3],[5,6]];

    protected $order_items = [
        [
            'product_id' => 1,
            'product_master_id' => 1,
            'quantity' => 1,
            'cost' => 200,
        ],
        [
            'product_id' => 2,
            'product_master_id' => 2,
            'quantity' => 2,
            'cost' => 680,
        ],
        [
            'product_id' => 3,
            'product_master_id' => 3,
            'quantity' => 1,
            'cost' => 530,
        ],

    ];
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function test()
    {
        echo "ok";
    }

    public function sqlAddress()
    {
        $provinces = $this->provinces;
        $id_p = 1; $id_d = 1; $id_a = 1;
        for ($p = 0; $p < count($provinces); $p++) {
            $province = $provinces[$p];
            echo "insert into provinces(id, name) values (".$id_p.", '".$province['p']."');<br>";
            for ($d = 0; $d < count($province['districts']); $d++) {
                $district = $province['districts'][$d];
                echo "insert into districts(id, province_id, name) values (".$id_d.", ".$id_p.", '".$district."');<br>";
                $address =  array_rand($this->address,3);
                foreach ($address as $a) {
                    echo "insert into addresses(id, district_id, address, date_created) values (".$id_a.", ".$id_d.", '".$this->address[$a]."', '2017-08-26 12:55:42');<br>";$id_a++;    
                } 
                $id_d++; 
            }

            $id_p++;
        }

    }

    public function sqlUser()
    {
        $users = $this->users;
        $id_u = 1;
        for ($u = 0; $u < count($users); $u++) {
            $user = $users[$u];
            echo "insert into users(id, email, password, name, phone, role, status, token, date_created, address_id) values (".$id_u.", '".$user[0]."', '$2y$10$K0WBhVJq.q9bEqpaF7OIYuOAFyfpf5uBNwMzz99jyhq85LTW1t2oK', '".$user[1]."','".$user[3]."', ".$user[2].", 1, null, '2016-06-06', ".$user[4].");<br>";$id_u++;
        }
        
    }

    public function sqlCategory()
    {
        $category = $this->category;
        $id_c = 1;
        $string = "insert into categories (id,name,alias,description,parent_id,date_created) values (";
        for ($c = 0; $c < count($category); $c++) {
            $cate = $category[$c];
            echo $string.$id_c.",'".$cate['p']."','".$this->slug($cate['p'])."','".$cate['p']."',null,'2017-08-16');<br>";
            $parent1 = $id_c;$id_c++;

            for ($chil1 = 0; $chil1 < count($cate['c']); $chil1++) {
                $children = $cate['c'][$chil1];
                echo $string.$id_c.",'".$children['p']."','".$this->slug($children['p'])."','".$children['p']."',".$parent1.",'2017-08-16');<br>";
                $parent2 = $id_c; $id_c++;
                foreach ($children['c'] as $children2) {
                    echo $string.$id_c.",'".$children2."','".$this->slug($children2)."','".$children2."',".$parent2.",'2017-08-16');<br>";
                    $id_c++;
                }
            }
        }
    }

    public function sqlProduct()
    {
        $products = $this->products;
        $views = $this->views;
        $id_p = 1;

        for($p = 0; $p < count($products); $p++) {
            $product = $products[$p];
            $int= rand(1402055681,1502055681);
                
            $string = date("Y-m-d H:i:s",$int);
            echo "insert into products (id,name,alias,price,intro,description,status,rate_sum,category_id,date_created,image,views,current_price) values (".$id_p.",'".$product[0]."','".$this->slug($product[0])."',".$product[1].",'".$product[2]."','".$product[2]."',1,0,".$product[3].",'".$string."','".$product[4]."',".$views[$id_p].",".$product[1].");<br>";$id_p++;

        }

    }

    public function sqlProductColorImage()
    {
        $product = 1;
        $arr = $this->arr;
        $arr_color = $this->arr_color;
        $dem = 1; $id_pci = 1; $id_image = 1; $id_pm = 1;
        for ($product = 1; $product < count($arr); $product++) {
            $count = $arr[$product]+1;
            for ($pci = 1; $pci < $count; $pci++) {
                $int= rand(1452055681,1502055681);
                $temp = $pci -1;$type = 1;
                $string = date("Y-m-d H:i:s",$int);
                $sql_image = "insert into images(id,image,status,type,product_color_image_id,date_created) values (";
                //insert product_color_images
                echo "insert into product_color_images(id,product_id,color_id,date_created) values (".$id_pci.",".$product.",".$arr_color[$product][$temp].",'".$string."');<br>";
                
                //insert images
                if ($temp != 0) $type = 2;
                echo $sql_image.$id_image.",'/img/products/image_".$product.$temp.".jpg',1,".$type.",".$id_pci.",'".$string."');<br>";$id_image++;
                echo $sql_image.$id_image.",'/img/products/image_".$product.$temp."_1.jpg',1,1,".$id_pci.",'".$string."');<br>";$id_image++;
                echo $sql_image.$id_image.",'/img/products/image_".$product.$temp."_2.jpg',1,".$type.",".$id_pci.",'".$string."');<br>";$id_image++;
                //insert product_masters;
                for ($size_id = 1;$size_id < 6; $size_id++) {
                    echo "insert into product_masters(id,product_id,color_id,size_id) values (".$id_pm.",".$product.",".$arr_color[$product][$temp].",".$size_id.");<br>";
                    $id_pm++;
                }
                $id_pci++;
                
            }
        }

    }
    public function sqlSaleProgram()
    {
        $count = 8;
        $name = ['yama no hi','mizu no hi','keirou no hi','midori no hi','ganjitsu','taiiku no hi','syuumatsu no hi','sinnen'];
        $sale = [10,20,25,15,50,30,35,40];
        $arr_product = [
            [11,1,4,6,7,8],
            [15,14,10,2,7,8],
            [16,17,11,6,7,12],
            [1,8,9,6,4,3],
            [16,15,14,13,12,8],
            [4,5,6,7,8,11],
            [3,8],
            [1,4,6,12,13],
        ];
        $id_sP = 1; $id_s = 1;
        for ($i = 0; $i < $count; $i++) {
            echo "insert into sale_programs(id,name,date_start,date_end,date_created,status) values (".$id_sP.",'".$name[$i]."','2017-09-09','2017-10-09','2017-09-08',0);<br>";
            for ($p_id = 0; $p_id < count($arr_product[$i]); $p_id++) {
                echo "insert into sales(id,product_id,sale_program_id,sale,date_created) values (".$id_s.",".$arr_product[$i][$p_id].",".$id_sP.",".$sale[$i].",'2017-09-15');<br>";$id_s++;
            }
            $id_sP++;
        }
        
    }

    public function sqlKeyword() 
    {
        $keywords = ['man', 'woman', 'yasui', 'takai','new', 'fashion','best'];
        $product_keywords = [
            [1,3,4,5,6,7,8,9,10],
            [1,3,4,5,6,7,8,9,10],
            [1,3,4,5,6,7,8,9,10],
            [1,3,4,5,6,7,8,9,10],
            [1,3,4,5,6,7,8,9,10],
            [1,3,4,5,6,7,8,9,10],
            [1,3,4,5,6,7,8,9,10]
        ];
        $id_k = 1; $id_pk = 1;
        for ($i = 0; $i < count($keywords); $i++) {
            echo "insert into keywords(id,keyword,date_created) values (".$id_k.",'".$keywords[$i]."','2017-09-08');<br>";
            for($j = 0; $j < count($product_keywords[$i]); $j++) {
                echo "insert into product_keywords(id,keyword_id,product_id) values (".$id_pk.",".$id_k.",".$product_keywords[$i][$j].");<br>";$id_pk++;
            }

            $id_k++;
        }
        
    }

    public function sqlReview()
    {
        $reviews = [
        "So good, I love going into this place to find gently used clothes for work. ",
        "Loved the customer service i received today . My sale associate Lucy was a tremendous help and is an asset to your company.",
        "Huge selection of previously owned clothing. More for the woman shopper.",
        "I like it",
        "it is comfortable",
        "It is so pretty"
        ];
        $cout_user = 5;$cout_product = 18;
        $score = [3, 4, 5];$id_r = 1;
        for($i = 1; $i <= $cout_product; $i++) {
            $product[] = $i;
        };
        for($i = 1; $i <= $cout_user; $i++) {
            $p = array_rand($product,5);
            for ($j = 0; $j < 5; $j++) {
                $s = array_rand($score,1);$r = array_rand($reviews,1);
                echo "insert into reviews(id,product_id,user_id,rate,content,date_created) values (".$id_r.",".$p[$j].",".$i.",".$s.",'".$reviews[$r]."','2017-09-09');<br>";$id_r++;
            }
        }
        
    }
    public function sqlComment()
    {
        $comments = [
            [
                'content' => 'Does this product also sell?',
                'reply' => [
                    'Yes, you can buy it at all shop!',
                    'Thank you!'
                ]
            ],
            [
                'content' => 'Does this product also sell?',
                'reply' => [
                    'Yes, you can buy it at all shop!',
                    'Thank you!'
                ]
            ],
            [
                'content' => 'Does this product also sell?',
                'reply' => [
                    'Yes, you can buy it at all shop!',
                    'Thank you!'
                ]
            ],
            [
                'content' => 'Does this product also sell?',
                'reply' => [
                    'Yes, you can buy it at all shop!',
                    'Thank you!'
                ]
            ],
            [
                'content' => 'Does this product also sell?',
                'reply' => [
                    'Yes, you can buy it at all shop!',
                    'Thank you!'
                ]
            ],
        ];
        $count_user = 5;$cout_product = 18;
        $id_c = 1;
        for($i = 1; $i <= $cout_product; $i++) {
            $product[] = $i;
        };

        $int= 1502055681; //2017-08-06 17:41:21
                            
        
        
        for($i = 1; $i <= $count_user; $i++) {
            $p = array_rand($product,5);
            for ($j = 0; $j < 5; $j++) {
                $r = array_rand($comments,1);
                $comment = $comments[$r];$int = $int + 1000;
                $date = date("Y-m-d H:i:s",$int);
                echo "insert into comments(id,product_id,user_id,status,content,date_created) values (".$id_c.",".$p[$j].",".$i.",1,'".$comment['content']."','".$date."');<br>";$id_c++;
                if (count($comment['reply']) > 0) {
                    $parent_id = $id_c - 1;
                    for ($k = 0; $k < count($comment['reply']); $k++) {
                    $int = $int + 1000;
                    $date = date("Y-m-d H:i:s",$int);
                    echo "insert into comments(id,product_id,parent_id,user_id,status,content,date_created) values (".$id_c.",".$p[$j].",".$parent_id.",".$i.",1,'".$comment['reply'][$k]."','".$date."');<br>";$id_c++;
                    }
                }
            }
        }
        
    }

    public function sqlOrder()
    {
        $arr = $this->arr;
        $products = $this->products;
        $id_p = 1;$j = 1;
        for ($i = 1; $i < count($arr); $i++) {
            while($arr[$i] != 0) {
                $pci_product[$j] = $i;$j++;
                $arr[$i]--;
            }
        }
        
        $quantity = [1 => 1,2 => 2,3 => 3,4 => 4,5 => 5];
        $status = [1 => 1, 2 => 2, 3 => 3];
        $q = array_rand($quantity,2);
        $users = $this->users;
        $id_o = 1; $id_oi = 1;
        for ($turn = 0; $turn < 10; $turn ++) {
            for ($u = 1; $u <= count($users); $u++) {
                $u_id = $u - 1;
                $int= rand(1492055681,1507055681);    
                $date = date("Y-m-d H:i:s",$int);
                $date_sip = date("Y-m-d H:i:s",$int + rand(2000, 5000));
                $date_complete = date("Y-m-d H:i:s",$int + rand(6000, 10000));
                $pcis = array_rand($pci_product,2);
                $q = array_rand($quantity,2);
                $cost1 = $products[$pci_product[$pcis[0]]][1] * $q[0];
                $cost2 = $products[$pci_product[$pcis[1]]][1] * $q[1];
                $cost = $cost1 + $cost2;
                $type = array_rand($status, 1);
                if ($type == 1) {
                    $string_sip = 'null';
                    $string_completed = 'null';
                } else if ($type == 2) {
                    $string_sip = "'".$date_sip."'";
                    $string_completed = 'null';
                } else {
                    $string_sip = "'".$date_sip."'";
                    $string_completed = "'".$date_complete."'";
                }
                echo "insert into orders (id, user_id, name, phone, email, address_id, number_of_items, cost, status,ship_at,completed_at, date_created) values (".$id_o.", ".$u.", '".$users[$u_id][1]."','".$users[$u_id][3]."', '".$users[$u_id][0]."', ".$users[$u_id][4].",2,".$cost.",".$type.",".$string_sip.",".$string_completed.",'".$date."');<br>";

                echo "insert into order_items(id, order_id, product_master_id, quantity, status, cost, date_created) values (".$id_oi.", ".$id_o.",".$pcis[0].",".$q[0].",1,".$cost1.",'".$date."');<br>";$id_oi++;
                echo "insert into order_items(id, order_id, product_master_id, quantity, status, cost, date_created) values (".$id_oi.", ".$id_o.",".$pcis[1].",".$q[1].",1,".$cost2.",'".$date."');<br>";$id_oi++;
                $id_o++;
            }
        }

    }

    public function slug($str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}