<?php

return [
    'supported_languages' => [
        '*'
    ],

    'prefix' => [
        //Anh //India //Philippines
        'table', 'tab.', 'figure', 'fig', 'fig.', 'diagram', 'graphic', 'illustration',
        //Indo
        'tabel', 'grafik', 'gambar', 'ilustrasi',
        //Tây Ba Nha
        'tabla', 'figura', 'cuadro', 'gráfico', 'ilustración',
        //Bồ Đào Nha
        'tabela', 'ilustração',
        //Đức
        'tafel', 'tabelle', 'figur', 'diagramm', 'grafik', 'abbildung', 'abb.',
        //Pháp
        'tableau', 'diagramme', 'graphique',
        //Thổ Nhĩ Kỳ - Turkish
        'tablo' , 'figür', 'ġekil', 'şekil', 'çizelge',
        //Balan - Polish
        'tablica', 'wykres', 'rysunek', 'fot.',
        //Thủy Điển - Swedish
        'tablå', 'tabell',
        //Italy
        'tabella', 'diagramma', 'illustrazione',
        //Hà Lan
        'figuur', 'grafisch', 'illustratie',
        //Nhật Bản //Trung quốc
        '表', '図', '図表', '図[\p{N}]', '圖',
        //Hàn
        '표', '\[표', '\<표', '그림', '\[그림', '\<그림',
        //Nga
        'tаблица',
        //Hungary
        'táblázat', 'ábra', 'térkép', 'grafikon', 'kép',
        // Slovenia
        'tabela', 'grafikon', 'zemljevid', 'graf', 'slika',
        // Phần lan
        'taulukko', 'kaavio', 'kartta', 'kaavio', 'kuva',
        // CH Séc
        'tabulka', 'figura', 'mapa', 'graf', 'obrázek',
        // Na uy
        'tabell', 'figur', 'kart', 'graf', 'bilde',
        // Đan mạch
        'tabel', 'figur', 'kort', 'graf', 'billede',
        //Malay
        'jadual', 'carta pai', 'rajah', 'graf', 'gambar', 'foto',
        //Philippines,
        'talahanayan', 'graph', 'pigura', 'fig.', 'ilustrasyon', 'blg.', 'larawan',
        //việt nam
        'bảng', 'hình', 'hỡnh', 'đồ thị', 'sơ đồ',
    ],

    'rules' => [
        'page' => 10,
        'reliability' => 0.9,
        'min_image_area' => [
            'table' => 240*240,
            'figure' => 300*300,
        ],
        'caption_words_count' => [
            'min' => 10,
            'max' => 20,
        ],
        'caption_ignore' => [
            'table of content', 'table of contents', 'table of figures', 'table index', 'figure index', //Anh
            'DAFTAR ISI', 'DAFTAR TABEL', 'DAFTAR GAMBAR', 'gambar judul halaman', 'tabel judul halaman', //Indo
            'tabla de contenidos', //Tây Ba Nha
            'tabela de conteúdo', //Bồ Đào Nha,
            'table des matières', 'table des matieres', //Pháp
            //Đức
            'ISI KANDUNGAN', 'SENARAI JADUAL', //Malay
            'MGA NILALAMAN', //Ph
            'DANH MỤC HÌNH', 'MỤC LỤC', //Viet Nam
        ],
    ]
];
