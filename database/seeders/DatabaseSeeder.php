<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Destinations;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // 2. Create Categories (Generic ones since data doesn't specify)
        $categories = [
            'Alam',
            'Air',
            'Taman Hiburan',
            'Budaya',
            'Kuliner'
        ];

        $categoryIds = [];
        foreach ($categories as $catName) {
            $cat = Category::firstOrCreate(['name' => $catName]);
            $categoryIds[$catName] = $cat->id;
        }

        // 3. Tourism Data
        $wisataData = [
            [
                'id' => 1,
                'nama' => "Terasering Panyaweuyan",
                'lokasi' => "Maja, Majalengka",
                'deskripsi' => "Terasering panyaweuyan atau juga dikenal dengan lembah panyaweuyan merupakan destinasi wisata yang terletak di desa Sukasari Kidul dan Tejamulya, berada di ketinggian 2000 mdpl membuat terasering panyaweuyan mempunyai udara yang sejuk sehingga cocok dijadikan tempat berlibur setelah lelah dengan pekerjaan dikota. Terasering panyaweuyan menyuguhkan pemandangan hamparan hijau pertanian sayur yang membuat mata kembali segar. Selain itu juga daru terasering panyaweuyan juga dapat melihat pemandangan gunung ciremai yang terlihat jelas karena terasering panyaweuyan terdapat di kaki gunung ciremai. Terdapat fasilitas yang kumplit, mulai dari tempat parkir,toilet sampai dengan mushola, terdapa juga spot foto untuk mengabadikan kenangan, karena lokasi ini berada di dataran tinggi, sebaiknya untuk yang membawa kendaraan pribadi untuk mengecek kembali kondisi kendaraan supaya lancar saat perjalanan. ",
                'jadwal' => "Senin - Minggu, 08:00 - 18:00",
                'harga' => "Rp 5,000/orang",
            ],
            [
                'id' => 2,
                'nama' => "Curug Muara Jaya",
                'lokasi' => "Argapura, Majalengka",
                'deskripsi' => "Curug muara jaya tergolong air terjun bertingkat atau cascade. Dengan keseluruhan, tinggi terjunan air ini mencapai sekitar 73 meter. Terdapat 2 tingkat air terjun, tingkat yang pertama merupakan air terjun utama, tempat muasalnya aliran sungai jatuh dari ketinggian mencapai sekitar 60 meter di dinding tebing berbatu. Sementara tingkat yang kedua merupakan air terjun yang lebih pendek, tinggi nya hanya sekitar 13 meter. Kawasan sekeliling curug muara jaya memiliki udara yang sejuk. Tanahnya sangat subur, sehingga banyak ditemui hamparan kebun Palawijaya dan Sayur Mayur. Di wilayah ini juga ditumbuhi banyak pohon kesemek. Terdapat fasilitas yang kumplit mulai dari arena parkir yang luas, terdapat banyak toilet,mushola, dan ada warung dan makanan. Serta dapat ditempuh dengan kendaraan roda dua maupun roda empat hingga sampai ke lokasi. ",
                'jadwal' => "Senin - Minggu, 09:00 - 17:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 3,
                'nama' => "Buper Panten Argalingga",
                'lokasi' => "Argalingga, Majalengka",
                'deskripsi' => "Bumi perkemahan panten Argalingga merupakan wisata hutan pinus yang berada dibawah kaki Gunung Ciremai. Wisata ini berada di Desa Argalingga,Kecamatan Argapura,Kabupaten Majalengka. Ditempat wisata ini para pengunjung akan dimanjakan dengan deretan pohon pinus yang tersusun rapi. Oleh karena itu, buper Panten Argalingga sangat instagramable untuk dijadikan spot berswafoto. Terdapat fasilitas yang kumplit mulai dari tempat parkir, toilet,mushola, sewa hammock dan tenda, penyewaan sepeda motor listrik, dan warung makanan dan minuman.",
                'jadwal' => "Senin - Minggu, 09:00 - 15:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 4,
                'nama' => "Curug Ibun Pelangi",
                'lokasi' => "Argapura, Majalengka",
                'deskripsi' => "Curug ibun pelangi berlokasi di desa Sukasari Kaler, Kecamatan Argapura, Kabupaten Majalengka, Jawa Barat. Keindahan curug ibun pelangi sudah banyak memikat setiap para pengunjung yang datang, dimana lokasi curug nya berada didalam sebuah goa. Goa alami tersebut dibawahnya mengalir aliran sungai jernih juga bersih, dindingnya dihiasi oleh tebing batu yang menjulang tinggi. Keindahan curug ibun pelangi semakin lengkap di waktu waktu tertentu, seperti pagi dan sore hari atau saat kemiringan matahari sekitar 60 derajat. Kenapa dijuluki curug pelangi? Karena diantara dua tebing lavanya kerap muncul pelangi. Curug yang terletak di punggung gunung Ciremai ini dikenal dengan sebutan Green Canyon. Bukan tanpa sebab, kawasan curug ini tidak hanya menyimpan pesona alam berupa air terjun saja, namun terdapat sungai yang memiliki air jernih dan juga dinding tebing dengan bentuknya yang melengkung agak tertutup sehingga tampak seperti gua, dinding tebing tersebut dibentuk secara alami dari bekuan aliran lava sejak berjuta juta tahun silam. Ditambah suasana dingin dan sejuk karena lokasinya terletak di punggung Gunung Ciremai. Terdapat fasilitas yang kumplit mulai dari mushola, toilet, gazebo, parkiran, kantin, keamanan.",
                'jadwal' => "Senin - Minggu, 09:00 - 16:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 5,
                'nama' => "Pendakian Gunung Ciremai Apuy",
                'lokasi' => "Argapura, Majalengka",
                'deskripsi' => "Pendakian via jalur Apuy Gunung Ciremai adalah jalur pendakian dari kabupaten Majalengka. Jalur Apuy ini merupakan jalur pendakian yang populer dan menjadi salah satu favorit bagi para pendaki. Selain jalur yang tidak terlalu panjang dan trek yang tidak terlalu ekstrim, pemandangan yang ada pada setiap perjalanan juga sangat indah. Gunung Ciremai adalah gunung tertinggi di Jawa Barat. Gunung ini memiliki ketinggian 3.978 mdpl ( meter diatas permukaan laut ). Gunung Ciremai berdiri diatas 3 kabupaten yaitu kabupaten Cirenon, Kabupaten Kuningan dan kabuoaten Majalengka, provinsi Jawa Barat. Jalur pendakian Gunung Ciremai terdiri dari 7 pos, dan banyak pendaki yang ngecamp di pos 5 dilanjutkan melakukan summit menuju puncak pada dini harinya. Para pendaki dapat mendaftar online dahulu sebelum pendakian dengan syarat cukup NIK dan No. HP. Terdapat fasilitas yang kumplit mulai dari kamar mandi, mushola, area parkir kendaraan, warung makanan dan kendaraan.",
                'jadwal' => "Senin - Minggu, 09:00 - 13:00",
                'harga' => "Rp 77,000/orang",
            ],
            [
                'id' => 6,
                'nama' => "Saung Gunung Beepark (Taman Bunga)",
                'lokasi' => "Argalingga, Majalengka",
                'deskripsi' => "Saung Gunung Beepark dibangun diatas tanah seluas 2 hektar yang berbatasan langsung dengan TNGC ( Taman Nasional Gunung Ciremai ). Beepark menjadi satu – satunya taman bunga terluas dan terfavorit di kabupaten Majalengka juga mempunyai fasilitas yang cukup lengkap. Seperti camping, ground, restoran sunda, penginapan, ruang serbaguna, dan lahan untuk berbagai acara seperti gathering family, gathering kantor, senam, dll.",
                'jadwal' => "Senin - Minggu, 09:00 - 17:00",
                'harga' => "Rp 20,000/orang",
            ],
            [
                'id' => 7,
                'nama' => "Situ Sangiang",
                'lokasi' => "Banjaran, Majalengka",
                'deskripsi' => "Menghabiskan waktu libur dengan berkunjung ke Situ Sangiang merupakan sebuah keputuasan yang tepat. Jauh dari hiruk pikuk perkotaan yang semakin membuat situasi disini bikin betah dan enggan untuk pergi meninggalkannya. Tempat wisata ini dianggap sebagai nilai sejarah, karena dipercaya bahwa danau inilah yang menjadi sebab hilangnya kerajaan Talaga Manggung serta terdapat makam sultan yang dikenal dengan Sunan Parung. Selain menjadi objek wisata alam, juga menjadi tempat wisata religi, pengunjung sekallian dapat berjiarah ke makam Sunan Parung. Situ sangiang dianggap juga sebagai tempat yang keramat. Terdapat banyak ikan yang bergerombolan, namun ikan tersebut tidak boleh ditangkap apalagi memakannya. Masyarakat sekitar percaya apabila memakan ikan tersebut akan membawa musibah untuk keluarga beserta keturunannya. Memiliki luas 100 hektar untuk hutan alami serta danau yang luasnya 19 hektar, membuat para pengunjung tidak ada hentinya untuk menelusuri area wisata tersebut. Selama peneluasuran pengynjung akan melihat hewan liat seperti lutung, surii, hingga kijang dan pohon – pohon yang umurnya sudah ratusan tahun. Terdapat fasilitas yang kumplit mulai dari tempat parkir kendaraan, toilet, mushola, warung wisata, gazebo, spot foto.",
                'jadwal' => "Senin - Minggu, 12:00 - 17:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 8,
                'nama' => "Sawiah",
                'lokasi' => "Sangiang, Majalengka",
                'deskripsi' => "Puncak Sawiah menjadi destinasi wisata baru di Majalengka Jawa Barat yang rekomended untuk anda kunjungi ketika ingin berlibur. Salah satu tempat dimana anda bisa merefresh jiwa dan pikiran yang penat akan beban pekerjaan maupun suasana perkotaan. Nikmati keindahan di Puncak Sawiah untuk perjalanan liburan yang tak terlupakan di Majalengka. Lokasinya yang ada di kawasan peginginagn membuat udara yang ada di tempat ini dangan segar dan menyejukan. Sajian spot foto yang instagramable dengan landscape pegunungan dan pemandangan hijau akan membawa sensasi perjalanan liburan anda akan semakin berkualitas dan menyenangkan. Pucak Sawiah tempat wisata alam di Majalengka yang menawarkan keindahan alam dari ketinggian, seperti arean persawahan, pegunungan serta udara yang sejuk. Terdapat fasilitas yang kumplit mulai dari warung, mushola, toilet, dan area parkir yang luas.",
                'jadwal' => "Senin - Minggu, 8:00 - 17:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 9,
                'nama' => "Bukit Kanaga Hil",
                'lokasi' => "Cipulus, Majalengka",
                'deskripsi' => "Bukit Kanaga terletak di kaki gunung ciremai bagian barat yang masih merupakan bagiandari kawasan TNGC tepatnya di Blok Ciinjuk Desa Cipulus, Kecamatan Cikijing, Kabupaten Majalengka, Jawa Barat dengan ketinggian kurang lebih 1300 hingga 1450 mdpl. Di Bukit Kanaga pengunjung bjsa menikmati alan dan pesona khas agraris. Hutan pinus menyajikan udara yang sejuk dan segar dengan wangi yang khas. Selain hutan pinus terdapat pemandangan terasering dari perkebunan warga. Warga di daerah ini umumnya memiliki kebun sayur sehingga menambah keindahan pemandangan. Lokasi wisata Bukit Kanaga memiliki hawa yang sejuk dan diselimuti oleh kabut tipis. Cocok untuk pecinta fotografi yang berkaitan dengan alam. Dan pada hari yang cerah, bukin kanaga menawarkan pemandangan langit biru dan terasering yang indah. Terdapat fasilitas yang kumplit mulai dari mushola, playground, toilet, spot foto, warung penjual makanan, dan gazebo.",
                'jadwal' => "Senin - Minggu, 09:00 - 17:00",
                'harga' => "Rp 20,000/orang",
            ],
            [
                'id' => 10,
                'nama' => "Kolam Renang Tohaga Indah",
                'lokasi' => "Burujul, Majalengka",
                'deskripsi' => "Kolam Renang Tohaga Indah terdapat di Desa Burujul Kulon, Jatiwangi, Kabupaten Majalengka, Jawa Barat. Kolam Renang Tohaga Indah merupakan salah satu tempat wisata yang ada di Majalengka. Setelah penat menyibukan diri di kota, berenang atau berendam di air bisa menjadi cara untuk merilexkan pikiran dan tubuh. Kolam renang ini semakin adem dan nyaman karena dikelilingi dengan bayak pohon sehingga tempatnya rindang dan tidak terpapar langsung oleh sinar matahari. Terdapat fasilitas yang kumplit mulai dari mushola,kolam renang anak/dewasa, permainan, gazebo, area parkir, kamar mandi, tempat ganti, kantin.",
                'jadwal' => "Senin - Minggu, 09:00 - 17:00",
                'harga' => "Rp 25,000/orang",
            ],
            [
                'id' => 11,
                'nama' => "Rajawali Water Park",
                'lokasi' => "Kadipaten, Majalengka",
                'deskripsi' => "Rajawali Water Park adalah sebuah kolam renang atau lokasi berenang yang berlokasi di Kadipaten, Kabupaten Majalengka, Jawa Barat. Rajawali Water Park adalah sebuah wisata taman rekreasi air yang menawarkan berbagai fasilitas kolam renang untuk keluarga dan pengunjung. Dilengkapi dengan berbagai jenis kolam, mulai dari kolam untuk anak-anak hingga kolam dewasa, serta wahana air seru seperti seluncuran dan permainan air lainnya, tempat ini cocok untuk aktivitas relaksasi dan rekreasi. Dengan suasana yang asri dan udara segar. Terdapat fasilitas yang kumplit mulai dari area parkir luas, toilet, mushola, kantin.",
                'jadwal' => "Senin - Minggu, 09:00 - 17:00",
                'harga' => "Rp 20,000/orang",
            ],
            [
                'id' => 12,
                'nama' => "Jembar Water Park ",
                'lokasi' => "Ranji Wetan, Majalengka",
                'deskripsi' => "Jembar water park ini taman air unik dengan seluncur berkelok, kolam renang luas, dan patung dinosaurus besar yang berwarna warni. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, mushola, kantin, gazebo. ",
                'jadwal' => "Senin - Minggu, 09:00 - 17:00",
                'harga' => "Rp 25,000/orang",
            ],
            [
                'id' => 13,
                'nama' => "Bukit Sanghyangdora",
                'lokasi' => "Leuwimunding, Majalengka",
                'deskripsi' => "Bukit dengan 360° view. Bisa menikmati city light dan sering disebut Raja Empat nya Majalengka. Bisa dijadikan tempat untuk berkemah bersama keluarga, pasangan, dan teman-teman. Terdapat fasilitas yang kumplit mulai dari toilet, area parkir, warung makanan/minuman, spot foto, mushola, area camping.",
                'jadwal' => "Senin - Minggu, 08:00 - 17:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 14,
                'nama' => "Kolam Renang Wisata Pager Toya",
                'lokasi' => "Parungjaya, Majalengka",
                'deskripsi' => "Kolam Renang Pager Toya adalah sebuah kolam renang atau lokasi berenang yang berlokasi di Parungjaya, Kec. Leuwimunding, Kabupaten Majalengka,Jawa Barat. Kolam Renang Pager Toya ini menawarkan suasana asri dengan kolam renang untuk anak dan dewasa. Tempat ini cocok untuk berlibur bersama keluarga sambil menikmati udara segar dan pemandangan alam yang indah. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, mushola, kantin, gazebo.",
                'jadwal' => "Senin - Minggu, 08:00 - 17:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 15,
                'nama' => "Taman Dinosaurus",
                'lokasi' => "Lemahsugih, Majalengka",
                'deskripsi' => "Taman Dinosaurus menjadi salah satu tempat wisata anak yang rekomended di Majalengka yang tak bleh gterlewatkan. Disini pengunjung dapat menemukan dua patung Brachiosaurus dengan ukuran yang berbeda. Pertama yang paling besar memiliki tinggi 17 meter dan panjang 25 meter. Sedangkan yang kedua memiliki tinggi 3 meter dan panjang 4  meter Selain patung, taman dinosaurus dilengkapi dengan taman agrowisata, hutan pinus, lapangan golf, kolam renang dan lainnya. Temoat wisata yang resmi dibuka pada Agustus 2003 ini berada pada lahan seluas 4 hektar tanah milik pribadi. Meskipun bukan termasuk dalam wisata terbaru Majalengka tetapi taman dinosaurus memberikan yang terbaik kepada penikmatnya. Terdapat fasilitas yang kumplit mulai dari, taman bermain, kolam renang, pusat kuliner untuk menikmati makanan dan minuman, mushola, toilet, dan area parkir yang luas.",
                'jadwal' => "Senin - Minggu, 08:00 - 19:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 16,
                'nama' => "Marlina Kopi",
                'lokasi' => "Lemahsugih, Majalengka",
                'deskripsi' => "Anda yang mencari cafe outdoor di Majalengka dengan nuansa alam yang sangat menyejukan, maka cobalah untuk datang ke Marlina Kopi, Cafe ini memiliki nuansa yang sangat kental dengan alam terbuka, cocok seklai jika anda ingin berlibur dan bersantai. Memasuki Marlina Kopi anda disajikan dengan sebuah pepohonan yang rimbun dengan suasana sejuk. Dijamin suasana yang ada di Marlina Kopi membuat Anda akan semakin betah. Terdapat fasilitas yang kumplit mulai dari area parkir luas, toilet bersih dan mushola menjadikan tempat yang nyaman dan ramah bagi semua kalangan.",
                'jadwal' => "Senin - Minggu, 08:00 - 22:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 17,
                'nama' => "Curug Tapak Kuda",
                'lokasi' => "Lemahsugih, Majalengka",
                'deskripsi' => "Curug Tapak Kuda memiliki tipe air terjun yang berunduk. Sebenarnya curug ini mempunyai nama lain yaitu Curug Citerus dan Curug Tapak Kuda. Akan tetapi, karena lokasinya yang berada di Dusun Tapak Kuda, maka masyarakat sekitar dan juga wisatawan menyebutnya sebagai Curug Tapak Kuda. Wisata air terjun ini memiliki ketinggian kurang lebih 20 meter. Salah satu yang menjadi keunikan adalah memiliki lima undakan. Aliran airnya yang masih bersih memberikan kesegaran tersendiriketika wisatawan membasuh wajahnya disini. Keindahan alam di sekitar air terjun sawah, bukit serta gunung yang memberikan pemandangan yang sangat indah. Terdapat fasilitas yang kumplit mulai dari gazebo untuk tempat bersantai, toilet, dan area parkir yang luas",
                'jadwal' => "Senin - Minggu, 08:00",
                'harga' => "Gratis",
            ],
            [
                'id' => 18,
                'nama' => "Kolam Renang Putri Borneo",
                'lokasi' => "Ligung, Majalengka",
                'deskripsi' => "Kolam Renang Putri Borneo adalah sebuah kolam renang atau lokasi berenang yang berlokasi di Majasari, Ligung, Kabupaten Majalengka, Jawa Barat. Kolam Renang Putri Borneo di Majalengka adalah sebuah fasilitas rekreasi yang populer di daerah tersebut, menawarkan suasana yang nyaman untuk berenang dan bersantai. Terdapat fasilitas yang kumplit mulai dari area parkir, cafe, penyewaan pelampung,WiFi gratis,kamar mandi, ruang ganti dan toilet",
                'jadwal' => "Senin - Minggu, 08:00 - 16:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 19,
                'nama' => "Paralayang Gunung Panten",
                'lokasi' => "Munjul, Majalengka",
                'deskripsi' => "Paralayang menawarkan pengalaman terbang yang memukau dengan pemandangan alam yang spektakuler. Memungkinkan para pengunjung untuk terbang melintasi perbukitan dan sawah yang hijau. Aktivitas ini cocok bagi para pecinta olahraga ekstrim maupun mereka yang ingin menikmati panorama indah dari ketinggian. Paralayang Majalengka menjadi destinasi wisata yang menarik bagi wisatawan lokal maupun mancanegara. Terdapat fasilitas yang kumplit mulai dari area parkir luas, toilet, gazebo, kantin makanan/minuman.",
                'jadwal' => "Senin - Minggu, 08:00 - 22:00",
                'harga' => "Rp 5,000/orang",
            ],
            [
                'id' => 20,
                'nama' => "Water Boom Tirta Bima",
                'lokasi' => "Majalengka, Majalengka",
                'deskripsi' => "Kolam Renang Water Boom Tirta Bima Majalengka adalah taman air yang menyajikan berbagai wahana seru dan menyenangkan untuk semua usia. Dengan fasilitas kolam renang yang luas, seluncuran air, serta area bermain yang aman, tempat ini menjadi destinasi favorit keluarga. Terdapat fasilitas yang kumplit seperti area parkir, kantin, kamar mandi, kamar ganti",
                'jadwal' => "Senin - Minggu, 08:00 - 17:00",
                'harga' => "Rp 25,000/orang",
            ],
            [
                'id' => 21,
                'nama' => "Istana Stable Equestrian Park",
                'lokasi' => "Cikebo, Majalengka",
                'deskripsi' => "Istana Stable Equestrian Park merupakan objek wisata baru di Majalengka yang menjadi daya tarik utama disini yaitu wahana berkuda yang menyajikan berbagai keseruan dan kegiatan asyik dengan fasilitas menarik didalamnya. Selain berkuda kalian juga bisa menikmati mini zoo yang didalamnya terdapat banyak jenis binatang dari kura kura, iguana, rusa, burung beo atau macan dan yang lainnya. Di mini zoo ini kalian bisa berinteraksi langsung dengan hewan yang ada disana, memberikan makan, hingga foto bareng. Tempat ini sangat cocok untuk menjadi tempat berlibur keluarga.  Terdapat fasilitas yang kumplit mulai dari area parkir, lapangan berkuda, toilet, mushola, cafe/resto, spot foto",
                'jadwal' => "Senin - Minggu, 08:00 - 17:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 22,
                'nama' => "Cikadongdong River Tubing ",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Cikadongdong River Tubing merupakan aktivitas wisata di sungai mengalir menggunakan ban karet sebagai sarananya. Sekilas, aktivitas wisata ini mirip arung jeram. Pengunjung dapat menikmati wisata Cikadongdong River Tubing ini bersama keluarga maupun teman sekantor untuk mengisi liburan dan mendapatkan pengalaman yang menyenangkan. Cikadongdong River Tubing akan menyusuri Sungai Cikadongdong yang memiliki arus tidak terlalu deras dan airnya jernih. Air sungai ini berasal dari Gunung Ceremai. Selama perjalanan menyusuri sungai, pengunjung juga dapat menikmati pemandang sekitar yang masih asri dan banyak batuan. Meskipun begitu, jalur Cikadongdong River Tubing dijamin aman karena pengelola telah mengatur jalur lintasan dan bebatuan. Pada beberapa bagian jalur akan ada penjaga untuk memastikan pengunjung tetap aman.Pengunjung yang ingin menjajal permainan ini diwajibkan menggunakan alat pengaman lengkap, seperti pelampung, helm, serta pelindung kaki dan tangan. Terdapat fasilitas yang kumplit mulai dari toilet, area parkir, dan jalur akses yang nyaman.",
                'jadwal' => "Senin - Minggu, 08:00 - 16:00",
                'harga' => "Rp 60,000/orang",
            ],
            [
                'id' => 23,
                'nama' => "Patilasan Prabu Siliwangi",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Patilasan Prabu Siliwangi berada di desa Pajajar, Kec. Rajagaluh Kabupaten Majalengka. Yang berada di kaki gunung ciremai sekitar 19 KM dari pusat kota Majalengka. Menurut cerita turun temurun dtw prabu siliwangi adalah tapak tilas atang pasanggrahan raja dari kerajaan pajajar yaitu Prabu Siliwangi dan menjadi salah satu dtw tertua di Majalengka yang mulai dikekola pada tahun 90an, berbasis wisata alam dan minat khusus religi, terdapat 2 danau dan 2 mata air serta pasanggrahan/makom, kolam renang anak, dan ikan terapi serta camping ground. Terdapat fasilitas yang kumplit mulai dari area parkir, warung makanan/minuman, toilet",
                'jadwal' => "Senin - Minggu, 24 jam",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 24,
                'nama' => "Cidewata",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Bumi Perkemahan (buper) Cidewata di hutan konservasi Taman Nasional Gunung Ciremai (TNGC) terletak di desa Payung, Rajagaluh, Majalengka, Jawa Barat. Buper Cidewata berada di ketinggian 1.078 mdpl. Suhu pada siang hari di area buper dibawah 25ºC dan pada malam hari mencapai 14ºC. Buper Cidewata memiliki lansekap alam yang sangat indah dan dikelilingi dengan pegunungan dan perbukitan. Wisatawan juga dapat menikmati pemandangan matahari terbit dan matahari terbenam. Matahari terbit yang indah dapat dilihat dari bukit Liang Angin, sedangkan dari sebelah barat buper Cidewata dapat dilihat matahari terbenam di balik gunung Tampomas, Sumedang.“Camping” atau berkemah merupakan alternatif liburan menarik yang dapat dilakukan di luar ruangan. Menikmati keindahan alam melalui kegiatan berkemah dapat mengurangi rasa jenuh akan hiruk pikuk aktivitas keseharian di perkotaan. Pemandangan alam yang indah dan sejuknya udara di kawasan buper Cidewata merupakan daya tarik utama wisatawan untuk berkunjung. Terdapat fasilitas yang kumplit mulai dari area parkir, area berkemah, sepeda gantung, toilet, mushola, warung makanan/minuman, ayunan, penyewaan tenda. ",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 25,
                'nama' => "Gunung Ciwaru",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Tempat wisata ini merupakan hasil dari wujud kerjasan antara pemuda serta masyarakat setempat yang mengelola secara mandiri. Wisata ini merupakan destinasi baru yang booming pada tahun 2017 yang lalu. Meskipun baru, lokasi ini jadi perburuan netizen. Gunung Ciwaru menyuguhkan panorama hutan pinus serta bisa melihat gunung ciremai. Buat kamu yang ingin menyaksikan keindahan dari panorama gunung ciremai dengan kabut bisa datang di pagi ataupun sore hari. Terdapat fasilitas yang lengkap mulai dari area parkir, gazebo, warung makanan/minuman, toilet, mushola, penyewaan alat berkemah.",
                'jadwal' => "Senin - Minggu, 08:00 - 16:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 26,
                'nama' => "Sharongghe",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Wisata air Sharongghe River Tubing berada di Desa Sadomas, Kecamatan Rajagaluh, Kabupaten Majalengka, Jawa Barat. Wisata Air Sharongghe atau Sharongghe River Tubing adalah wisata yang disebut menantang karena jalur destinasi alamnya melalui sungai.Nantinya wisatawan akan dapat menikmati tantangan wisata air ini dengan menelusuri sungai yang dikelilingi batu-batu besar. Namun ketika menjelang finish, seluruh perjuangan melintasi bahaya terbayar lunas. Wisatawan bisa bersantai menikmati laju perahu ban yang tenang.Sepanjang perjalanan, wisatawan disuguhi pemandangan mempesona, mulai dari pohon bambu yang rimbun, ladang yang menghijau, hingga alam pedeasri, Seluruhnya masih asri.Terdapat fasilitas yang kumplit mulai dari area parkir, taman bermain anak, toilet, kantin. ",
                'jadwal' => "Senin - Minggu, 08:00 - 17:00",
                'harga' => "Rp 5,000/orang",
            ],
            [
                'id' => 27,
                'nama' => "Situ Janawi",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Situ janawi merupakan tempat wisata berupa telaga kecil yang mempunyai karakteristik tersendiri yang terletak di kaki gunung ciremai. Air nya seger dan asri dan pemandangan yang akan membuat anda kembali merasa segar. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, warung makanan/minuman, pelampung. ",
                'jadwal' => "Senin - Minggu, 08:00 - 18:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 28,
                'nama' => "Sawah Bengkok",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Sawah bengkok merupakan tempat wisata yang berada di majalengka. Sawah bengkok adalah sebuah tempat wisata berupa kolam renang yang menyuguhkan pemandangan hamparan sawah. Wisata ini berdiri diatas tanah bengkok milik desa teja, kecamatan Rajagaluh, kabupaten majalengka. Pihak desa menyulap area sawah seluas 1.400 meter persegi itu menjadi mirip seperti di ubud bali. Terdapat fasilitas yang kumplit mulai dari area parkir, kamar mandi, kuliner, selfie area, spot foto. ",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 29,
                'nama' => "Situ Ciranca",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Situ Ciranca, yang terletak di Desa Teja, Kecamatan Rajagaluh, Kabupaten Majalengka, Jawa Barat, adalah destinasi wisata alam yang menawarkan suasana menenangkan dengan cuaca sejuk, air jernih, dan pepohonan rindang. Awalnya sebuah bendungan yang digunakan untuk menampung air, tempat ini kini berfungsi sebagai tempat rekreasi setelah ditata sejak tahun 2019. Terletak di lereng Gunung Ciremai, Situ Ciranca menarik pengunjung yang ingin menikmati pemandangan indah dan suasana pedesaan yang asri. Terdapat fasilitas mulai dari area parkir, tempat bermain anak, berkemah, berkuda, toilet.",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 5,000/orang",
            ],
            [
                'id' => 30,
                'nama' => "Aryakibansland",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Aryakibansland merupakan sebuah tempat wisata baru berupa kolam renang yang berada tepat di bantaran Sungai Ciputri yang berada di Desa Rajagaluh Kidul, Kecamatan Rajagaluh, Kabupaten Majalengka. Fasilitas disini ada tiga konsep yaitu wisata untuk anak-anak, dewasa dan orang tua yang memadukan sungai dan kolam renang, ada terapi ikan dan river tubing. Jadi untuk orang tua sambil nunggu anaknya berenang bisa terapi ikan, untuk yang dewasa bisa coba river tubing dengan panjang jalur 350 meter. Terdapat fasilitas yang kumplit mulai dari area parkir, kolam renang, wahana olahraga air river tubing, toilet, gazebo, kantin. ",
                'jadwal' => "Senin - Minggu, 06:00 - 16:00",
                'harga' => "Rp 7,000/orang",
            ],
            [
                'id' => 31,
                'nama' => "Dn water play",
                'lokasi' => "Leuwimunding, Majalengka",
                'deskripsi' => "Dn waterplay merupakan tempat wisata buatan dengan konsep wisata air berwahana water boom, kolam pantai, dan kolam prestasi. Terdapat fasilitas kumplit mulai dari area parkir, toilet, gazebo, terapi ikan, kantin. ",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 32,
                'nama' => "Curug kiara danu",
                'lokasi' => "Sidangwangi, Majalengka",
                'deskripsi' => "Curug kiara danu merupakan  objek wisata terbaru yang ada di Kabupaten Majalengka. Air terjun buatan ini ada di desa lengkong kulon, kecamatan sindangwangi, Jawa Barat. Hal menarik salah satunya terdapat kolam renang bersebelahan langsung dengan hamparan persawahan, bukit, dan pepohonan. Keberadaan nya yang terletak di pegunungan tentu menyuguhkan keindahan alam yang mempesona serta menyajikan suasana yang segar nan asri, memiliki konsep kekinian yang mampu menarik hati para wisatawan. Terdapat fasilitas kumplit mulai dari area parkir, terapi ikan, toilet, gazebo, kantin. ",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 33,
                'nama' => "Jalur pendakian sadarehe",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Taman Nasional Gunung Ciremai (TNGC) meresmikan jalur pendakian baru yakni jalur pendakian Trisakti Sadarehe di Desa Payung, Kecamatan Rajagaluh, Kabupaten Majalengka, Jawa Barat. para pendaki yang melalui jalur ini rencananya akan mendapatkan banyak fasilitas. Para pendaki tidak perlu membawa air karena ketersediaan air yang melimpah. Mereka juga tidak perlu membawa tenda, karena panitia telah menyediakan. Terdapat fasilitas yang kumplit mulai dari ketersediaan air yang melimpah, ketersediaan peralatan, Padang savana di ketinggian 2.670 mdpl, Pemandangan matahari terbit dan terbenam, Keindahan hamparan Edelweis.",
                'jadwal' => "Senin - Minggu, 06:00 - 00:00",
                'harga' => "Rp 20,000/orang",
            ],
            [
                'id' => 34,
                'nama' => "Situ cipanten",
                'lokasi' => "Sindang, Majalengka",
                'deskripsi' => "Magical of cipanten yang dimana airnya bisa berubah-rubah loh saat musim penghujan warna air menjadi biru, dan musim kemarau berwarna hijau, dan juga banyak wahana seru lainnya. Terdapat fasilitas mulai dari area parkir, toilet, kamar ganti, warung makanan minuman, penyewaan pelampung. ",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 35,
                'nama' => "Talaga herang ",
                'lokasi' => "Sindangwangi, Majalengka",
                'deskripsi' => "Talaga Herang menjadi salah satu dari sekian banyaknya tempat wisata Majalengka paling hits dengan suguhan panorama menawan dan menakjubkan. Wisa ini memiliki danau kecil dengan air yang amat sangat jernih serta dihiasi ikan – ikan luci didalamnya. Air di talaha herang ini berasal dari gunung ciremai dan mengalir dari sungai bawah tanah. Aktivitas mandi di talaga herang menjadi daya tarik utama dari destinasi wisata satu ini. Panorama talaga herang menyajikan kolam dengan air jernih kebiruan serta pemandangan hijau yang mempesona rekomended untuk liburan keluarga. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet,memberi makan ikan, warung makanan/minuman.",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 5,000/orang",
            ],
            [
                'id' => 36,
                'nama' => "Buper talaga pancar",
                'lokasi' => "Sindangwangi, Majalengka",
                'deskripsi' => "Salah satu destinasi yang menarik untuk dikunjungi di Desa Lengkong, Kecamatan Sindangwangi, Kabupaten Majalengka, Jawa Barat, yaitu wana wisata Talaga Pancar.Talaga Pancar merupakan sebuah area bumi perkemahan (Buper) yang dikelilingi oleh hutan pinus yang mampu menghadirkan suasana nyaman untuk merelaksasi diri dari penatnya hiruk pikuk perkotaan. Terdapat fasilitas yang kumplit mulai dari area parkir, area playground anak, toilet, mushola, warung makanan/minuman.",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 5,000/orang",
            ],
            [
                'id' => 37,
                'nama' => "Terasering Ciboer Pas",
                'lokasi' => "Sindangwangi, Majalengka",
                'deskripsi' => "Ciboer Pass merupakan salah satu tempat wisata yang  hits di Majalengka, Jawa Barat. Dengan menampilkan terasering sawah, Ciboer Pass memiliki sajian yang menakjubkan dan banyak spot untuk berfoto.Pemandangan pesawahan yang indah dan segarnya udara pegunungan membuat pengalaman berkunjung ke Ciboer Pass semakin istimewa. Pemandangan Gunung Ciremai juga memperindah latar belakang terasering ini. Terdapat fasilitas yang kumplit mulai dari area parkir, gazebo, kafe, toilet, camping ground, villa.",
                'jadwal' => "Senin - Minggu, 06:00 - 22:00",
                'harga' => "Rp 5,000/orang",
            ],
            [
                'id' => 38,
                'nama' => "Curug Cipeuteuy",
                'lokasi' => "Sindangwangi, Majalengka",
                'deskripsi' => "Keindahan pesona wisata dari Air Terjun Cipeteuy akan memanjakan perjalanan liburan bersama orang – orang tercinta. Untuk bisa mnecapai titik pasti curug ini, wisatawan harus melawati jalan hutan. Hal ini dikarenakan lokasinya berada di tengah hutan dengan  lokasi curug yang seperti ini mendukung para pecinta jungle trekking. Pepohonan yang ada di hutan sekitar masih sangat asri dan rindang. Dengan nuansa hutan pinus menjadikan tempat ini nyaman untuk dikunjungi serta kesejukan air terjun yang mengalir. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, gazebo, kantin, camping, jalur hiking.",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 39,
                'nama' => "Buper Awilega",
                'lokasi' => "Sindangwangi, Majalengka",
                'deskripsi' => "Buper awilega terletak di desa bantaragung, kecamatan sindangwangi. Pengalaman berkemah disini memberikan kenyamanan tersendiri bagi para pengunjung. Terletak dikaki gunung Ciremai, buper awilega menawarkan pengalaman eksplorasi keindahan alam yang menawan. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, mushola, persewaan alat, wifi area,warung makanan/minuman.",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 40,
                'nama' => "Curug leles",
                'lokasi' => "Sindangwangi, Majalengka",
                'deskripsi' => "Curug Leles terletak di Taman Nasional Gunung Ciremai di Desa Padaherang, Kecamatan Sindangwangi, Kabupaten Majalengka, Jawa Barat. Curug Leles merupakan tempat berkemah dan wisata dengan keindahan alam yang memukau. Tempat wisata ini dapat digunakan untuk mengisi liburan bersama keluarga dan teman. Curug Leles memiliki ketiggian sekitar 7 meter dengan pemandangan alam yang indah di sekitarnya. Airnya terlihat segar dan debit airnya melimpah. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, mushola,warung makanan/minuman.",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 41,
                'nama' => "Batu Nyongclo",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Sebagian kawasan Kabupaten Majalengka, Jawa Barat, yang berada di kaki Gunung Ciremai menyimpan beragam pesona wisata alam yang memikat hati dan mata, salah satunya adalah destinasi Batu Nyongclo. Pesona destinasi Batu Nyongclo yang tepatnya berada di Desa Payung Kecamatan Rajagaluh, Kabupaten Majalengka ini bisa dikatakan surga tersembunyi bagi para pecinta keindahan alam. Pasalnya, pesona wisata Batu Nyongclo Majalengka, tak hanya sekadar dipandang sebagai tempat rekreasi biasa, melainkan petualangan penuh gaya kekinian dengan menawarkan spot-spot instagramable telah menunggu untuk dieksplorasi oleh pengunjungnya yang datang. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, gazebo, warung makanan/minuman.",
                'jadwal' => "Senin - Minggu, 06:00 - 24:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 42,
                'nama' => "Tirta indah waterboom",
                'lokasi' => "Rajagaluh, Majalengka",
                'deskripsi' => "Objek wisata keluarga ini, selain terdapat kolam renang yang lengkap, wisata ini terdapat pula mini zoo yang bisa dijadikan bahan edukasi kepada anak – anak untuk mengenalkan dunia satwa. Ada playground sebagai alternatif bagi yang tidak berenang. Bahka terdapat kolam terapi yang cocok bagi pengunjung dewasa. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, gazebo, mushola, kantin. ",
                'jadwal' => "Senin - Minggu, 06:00 - 16:00",
                'harga' => "Rp 25,000/orang",
            ],
            [
                'id' => 43,
                'nama' => "Kolam Renang Khuzama",
                'lokasi' => "Jatipamor, Majalengka",
                'deskripsi' => "Kolam Renang Dan Saung Botram Khuzama adalah sebuah kolam renang atau lokasi berenang yang berlokasi di Jatipamor, Talaga, Kabupaten Majalengka, Jawa Barat. Terdapat fasilitas mulai dari area parkir, kamar mandi, kantin. ",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 15,000/orang",
            ],
            [
                'id' => 44,
                'nama' => "Buper Gunung Putri",
                'lokasi' => "Talaga, Majalengka",
                'deskripsi' => "Buper gunung putri adalah salah satu wisata alam yang terletak dikawasan taman nasional gunung ciremai, berlokasi di blok citaman desa gunung manik kecamatan talaga, kabupaten majalengka. Disini kita bisa menikmati sejuknya hamparan hutan pinus alami dan terbentang pemandangan yang dapat memanjakan mata. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, warung. ",
                'jadwal' => "Senin - Minggu, 06:00 - 00:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 45,
                'nama' => "Gunung Laya",
                'lokasi' => "Talaga, Majalengka",
                'deskripsi' => "Pesona eksotis objek wisata Gunung Laya atau lebih dikenal dengan nama GL 20 yang berada di Desa Argasari, Kecamatan Talaga, Kabupaten Majalengka, Jawa Barat memiliki daya tarik tersendiri bagi para pengunjungnya. Destinasi yang letaknya berjarak sekitar 4,5 kilometer dari Alun-alun Kecamatan Talaga ini cukup mudah akses. Walau medan jalannya cenderung menanjak, namun jika dengan berkendara, pengunjung hanya butuh waktu sekitar 10 menit bisa sampai ke lokasi. Terdapat fasilitas yang kumplit mulai dari area parkir, gazebo, toilet, karaoke, caffe, mushola, wahana bermain anak. ",
                'jadwal' => "Senin - Minggu, 06:00 - 17:00",
                'harga' => "Rp 10,000/orang",
            ],
            [
                'id' => 46,
                'nama' => "Taman Keliling Dunia Majalengka",
                'lokasi' => "Cikasarung, Majalengka",
                'deskripsi' => "Kota Majalengka di juluki sebagai kota Angin karena secara geografis terletak di kaki gunung Ciremai. Selain itu kota Majalengka juga menyimpan destinasi wisata yang sangat menarik untuk dikunjungi. Diantara banyaknya wisata yang terkenal di Majalengka, ada wisata terbaru yang sedang menarik perhatian banyak orang belakangan ini. Wisata baru Majalengka ini bernama Taman Keliling Dunia Majalengka, wisata baru yang mengusung konsep menarik dan unik, dimana pengunjung bisa menjelajahi keindahan dunia dalam satu lokasi. Terdapat fasilitas yang kumplit mulai dari area parkir, toilet, mushola, foodcourt, mini zoo, penyewaan kostum. ",
                'jadwal' => "Senin - Minggu, 06:00 - 20:00",
                'harga' => "Rp 25,000/orang",
            ],
        ];

        // 4. Loop through data to create Admins and Destinations
        foreach ($wisataData as $data) {
            // Create Admin for this destination
            $admin = User::updateOrCreate(
                ['email' => "admin{$data['id']}@gmail.com"],
                [
                    'name' => "Admin " . $data['nama'],
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                ]
            );

            // Determine Category (Simple logic based on keywords or default)
            $categoryId = $categoryIds['Alam']; // Default
            if (Str::contains(strtolower($data['nama']), ['water', 'kolam', 'curug', 'situ', 'river', 'tirta'])) {
                $categoryId = $categoryIds['Air'];
            } elseif (Str::contains(strtolower($data['nama']), ['taman', 'park', 'land', 'dunia'])) {
                $categoryId = $categoryIds['Taman Hiburan'];
            } elseif (Str::contains(strtolower($data['nama']), ['patilasan', 'sejarah'])) {
                $categoryId = $categoryIds['Budaya'];
            } elseif (Str::contains(strtolower($data['nama']), ['kopi', 'cafe', 'kuliner'])) {
                $categoryId = $categoryIds['Kuliner'];
            }

            // Parse Price
            $priceString = preg_replace('/[^0-9]/', '', $data['harga']);
            $price = (int) $priceString;
            if ($price == 0 && strtolower($data['harga']) == 'gratis') {
                $price = 0;
            }

            // Create Destination
            Destinations::updateOrCreate(
                ['name' => $data['nama']],
                [
                    'slug' => Str::slug($data['nama']),
                    'description' => $data['deskripsi'],
                    'location' => $data['lokasi'],
                    'category_id' => $categoryId,
                    'ticket_price' => $price,
                    'user_id' => $admin->id,
                    'operating_hours' => $data['jadwal'],
                ]
            );
        }
    }
}
