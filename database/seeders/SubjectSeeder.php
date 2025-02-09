<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $subjects = [
            [
                "name" => "قضايا مجتمعية",
                "code" => "050مج",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "تتابعية طباقية واحواض ترسيب",
                "code" => "420ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "كيمياء تحليلية لطلاب البيولوجى",
                "code" => "460ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "فيزياء عامة (2)",
                "code" => "105ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "كيمياء عامة (2)",
                "code" => "105ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "كيمياء عضوية لغير طلاب الكيمياء",
                "code" => "211ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "احتمالات (1)",
                "code" => "242رأ",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "حزم البرامج الرياضية والاحصائية",
                "code" => "300رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "وراثة جزئيه",
                "code" => "308ز",
                "department_id" => null,
                "description" => ""
            ],
            [
                "name" => "مجتمعات نباتية",
                "code" => "342ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "حيود الاشعة وتطبيقاتها",
                "code" => "352ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "ميكروبيولوجيا الغذاء",
                "code" => "498ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "تاريخ العلوم",
                "code" => "012مج",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "التفكير العلمى",
                "code" => "014مج",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "لغة انجليزية(1)",
                "code" => "015مج",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "لغة انجليزية(2)",
                "code" => "020مج",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "التغذية الصحية",
                "code" => "13مج",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "مساحة مستوية",
                "code" => "200هـ",
                "department_id" => null,
                "description" => ""
            ],
            [
                "name" => "علم الطبقات",
                "code" => "210ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "كيمياء عضوية(1)",
                "code" => "210ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "علم الانسجة",
                "code" => "212ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "معادلات تفاضلية (1)",
                "code" => "212ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "كيمياء عضوية (2)",
                "code" => "212ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "بيولوجيا جزيئية",
                "code" => "212ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "معادلات تفاضلية لغير طلاب الرياضيات",
                "code" => "214ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "الكيمياء العضوية البيئية الخضراء",
                "code" => "214ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "أساسيات الوراثة",
                "code" => "215ز",
                "department_id" => null,
                "description" => ""
            ],
            [
                "name" => "فيزياء حديثة",
                "code" => "215ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "حفريات فقارية و اصل الانواع",
                "code" => "216ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "لافقاريات (2)",
                "code" => "222ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "بيئة حيوان",
                "code" => "225ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "مبادىء الفيزياء الحديثة",
                "code" => "225ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "كهرباء و تيار متردد",
                "code" => "226ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "المعادن المكونة للصخور",
                "code" => "230ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "كيمياء فيزيائية(1)",
                "code" => "230ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "بلورات ومعادن",
                "code" => "231ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "الميكانيكا النيوتونية",
                "code" => "231ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "فقاريات(1)",
                "code" => "232ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "الميكانيكا التحليلية",
                "code" => "232ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "فيزياء البلازما وتطبيقاتها",
                "code" => "232ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "كيمياء فيزيائية(2 )",
                "code" => "232ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "تصنيف نباتات زهرية",
                "code" => "232ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "علم البلورات",
                "code" => "233ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "بلورات وبصريات المعادن",
                "code" => "234ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "كيمياء حاسوبية",
                "code" => "234ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "نظم المعلومات الجغرافية(GIS)",
                "code" => "240ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "حشرات عامة",
                "code" => "240ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "مقدمة فى التحليل الكيميائى الكمى",
                "code" => "240ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "احصاء حيوى",
                "code" => "241رأ",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "مورفولوجيا الحشرات",
                "code" => "242ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "اساسيات الجيوفيزياء",
                "code" => "250ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "البرمجة الشيئية",
                "code" => "251رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "هياكل بيانات",
                "code" => "252رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "انزيمات وهرمونات نباتية",
                "code" => "252ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "والسيراميكيات",
                "code" => "256ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "تصنيف فطريات(1)",
                "code" => "262ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "معمل كهربية ومغناطيسية وتيار متردد",
                "code" => "265ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "بصريات فيزيائية والياف بصرية",
                "code" => "271ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "معمل ديناميكا حرارية وبصريات فيزيائية",
                "code" => "275ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "بيولوجيا الاسماك",
                "code" => "280ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "ميكروبيولوجيا عامة",
                "code" => "291ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "استزراع سمكى",
                "code" => "295ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "علم الانزيمات",
                "code" => "302نت",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "التقنيه الحيويه للطحالب",
                "code" => "304نت",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "المعالجه البيولوجيه للبيئه",
                "code" => "306نت",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "جيولوجيا حقلية",
                "code" => "306ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "اعاده التدوير وادارة النفايات",
                "code" => "310نت",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "فسيولوجيا الحيوان 2",
                "code" => "310ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "ميكانيكا الكم (1)",
                "code" => "311ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "تحليل حقيقى (1)",
                "code" => "312ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "بيولوجية الخلية",
                "code" => "312ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "النظرية الكهرومغناطيسية والديناميكا الكهربية",
                "code" => "312ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "كيمياء حيوية ومنتجات طبيعية",
                "code" => "312ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "حزازيات وتريديات ومعراة بذور",
                "code" => "312ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "الكيمياء الضوئية والوسائط النشيطة",
                "code" => "313ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "هندسة وراثية",
                "code" => "314ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "الكيمياء العضوية التخليقية المتقدمة",
                "code" => "314ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "موضوعات مختارة فى الرياضيات (1)",
                "code" => "315ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "حفريات دقيقة وجيولوجيا تاريخية",
                "code" => "315ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "معمل الفيزياء الحديثة(1)",
                "code" => "315ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "كيمياء الانسجة",
                "code" => "316ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "مناعة لطلاب البيوتكنولوجي",
                "code" => "318ط",
                "department_id" => null,
                "description" => ""
            ],
            [
                "name" => "بيولوجيا جزيئية وخلية",
                "code" => "318ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "معادلات تفاضلية جزئية ودوال خاصة",
                "code" => "318ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "مناعة",
                "code" => "318ط",
                "department_id" => null,
                "description" => ""
            ],
            [
                "name" => "ممتددات",
                "code" => "319ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "معمل كيمياء غير عضوية(1)",
                "code" => "320ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "كيمياء غير عضوية (2)",
                "code" => "321ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "طفيليات",
                "code" => "321ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "نظرية الحلقات والحقول",
                "code" => "322ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "فيزياء حيوية",
                "code" => "323ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "بيئة مائية",
                "code" => "323ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "تحليل عددى(1)",
                "code" => "323ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "مبادىء علم الصخور",
                "code" => "324ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "علم الأوليات والطفيليات",
                "code" => "324ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "كيمياء غير عضوية (3)",
                "code" => "324ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "معالجات دقيقة",
                "code" => "325هـ",
                "department_id" => null,
                "description" => ""
            ],
            [
                "name" => "بحوث العمليات (1)",
                "code" => "326ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "الاسس الرياضية لنظرية الكهرومغناطيسية والنسبية الخاصة (1)",
                "code" => "332ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "كيمياء فيزيائية(3)",
                "code" => "332ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "تصنيف نباتات زهرية متقدم",
                "code" => "332ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "طرق رياضية",
                "code" => "334ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "اساسيات علم الاجنة",
                "code" => "334ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "كيمياء التأكل",
                "code" => "334ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "فلورا مصر",
                "code" => "334ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "بيئات وأحواض ترسيب",
                "code" => "335ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "علم أصل الصخور المتحولة",
                "code" => "336ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "ميكانيكا الصخور والجيولوجيا التركيبية",
                "code" => "340ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "الحشرات الاقتصادية",
                "code" => "342ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "الفيزياء النووية(1)",
                "code" => "342ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "كيمياء تحليلية(1)",
                "code" => "342ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "حشرات طبية",
                "code" => "344ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "فيزياء اشعاعية",
                "code" => "344ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "مبادئ الجيولوجيا التركيبية",
                "code" => "345ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "معمل الفيزياء النووية",
                "code" => "345ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "عمليات عشوائية وتطبيقاتها",
                "code" => "346رأ",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "علم سيزمية الزلازل والاستكشاف السيزمى",
                "code" => "350ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "مبادىء فيزياء الجوامد",
                "code" => "350ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "الذكاء الاصطناعى",
                "code" => "352رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "كيمياء حيوية نباتية",
                "code" => "352ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "شبكات الحاسب",
                "code" => "354رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "موضوعات مختارة فى علوم الحاسب",
                "code" => "355رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "معمل فيزياء الجوامد(1)",
                "code" => "355ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "مقدمة فى الحسابات",
                "code" => "356رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "الاستكشاف الكهربى",
                "code" => "358ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "تصنيف فطريات (2)",
                "code" => "362ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "فطريات ممرضة للنبات",
                "code" => "364ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "معمل الكترونيات",
                "code" => "365ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "معالجة الصور",
                "code" => "366رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "علاقة العائل بالطفيل",
                "code" => "366ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "بيئة طحالب",
                "code" => "374ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "ايض ميكروبى",
                "code" => "392ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "انزيمات ميكروبية",
                "code" => "394ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "ميكروبيولوجيا صناعية",
                "code" => "396ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "مقال او بحث",
                "code" => "400ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "مقال و بحث",
                "code" => "400ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "مقال او بحث",
                "code" => "400ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "مقال وبحث",
                "code" => "400ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "المقال والبحث",
                "code" => "400رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "مقال او بحث",
                "code" => "400ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "مقال او بحث",
                "code" => "400ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "موضوعات مختارة فى علم الحيوان(2)",
                "code" => "402ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "وراثة عشائر",
                "code" => "402ز",
                "department_id" => null,
                "description" => ""
            ],
            [
                "name" => "جيولوجيا تحت سطحية",
                "code" => "409ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "خريطة مصر الجيولوجية",
                "code" => "410ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "علم المناعة",
                "code" => "412ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "تحليل مركب",
                "code" => "412ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "كيمياء عضوية تطبيقية",
                "code" => "412ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "كيمياء الجزيئات الحيوية",
                "code" => "413ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "بيئة الاحياء القديمة وطباقية حياتية",
                "code" => "414ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "معادلات تفاضلية جزئية",
                "code" => "414ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "موضوعات مختارة فى الكيمياء العضوية",
                "code" => "414ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "جيولوجية مصر",
                "code" => "415ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "معمل كيمياء عضويه متقدمه",
                "code" => "415ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "مقدمة علم الأجنة والتطور",
                "code" => "418ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "بنية الحاسب",
                "code" => "421هـ",
                "department_id" => null,
                "description" => ""
            ],
            [
                "name" => "فيزياء الحرارة المنخفضة والموصلية الفائقة",
                "code" => "422ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "تحليل عددى (2)",
                "code" => "424ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "بحوث عمليات (2)",
                "code" => "426ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "معمل كيمياء فيزيائية(2)",
                "code" => "431ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "تشريح مقارن للفقاريات",
                "code" => "432ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "اطياف ذرية وجزيئية",
                "code" => "432ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "كيمياءالسطوح والكيمياء الكهربية",
                "code" => "432ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "جيوكيمياء",
                "code" => "433ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "الجيولوجيا الاقتصادية",
                "code" => "434ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "الجيولوجيا الاقتصادية (جيوفيزياء)",
                "code" => "434ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "نمذجة رياضية",
                "code" => "434ر",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "استكشاف معدنى ومعادن وصخور صناعية",
                "code" => "435ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "مقدمة فى الجيولوجيا الطبية و علم البراكين",
                "code" => "436ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "عمليات تكوين الخامات",
                "code" => "438ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "علم الاجنة التجريبى",
                "code" => "438ح",
                "department_id" => 6,
                "description" => ""
            ],
            [
                "name" => "كيمياءتحليلية (2)",
                "code" => "441ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "احصاء رياضى",
                "code" => "442رأ",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "بيئة النباتات الجفافية والملحية",
                "code" => "442ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "فيزياء اشعاعية وتلوث اشعاعى ووقاية من الاشعاع",
                "code" => "444ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "موضوعات مختارة فى الكيمياء التحليلية",
                "code" => "444ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "جيولوجيا تصويرية واستشعار عن بعد",
                "code" => "445ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "طرق التحليل الآلي",
                "code" => "445ك",
                "department_id" => 3,
                "description" => ""
            ],
            [
                "name" => "حسابات علمية(1)",
                "code" => "451رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "فيزياء اشباه الموصلات والاغشية الرقيقة وتطبيقاتها",
                "code" => "451ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "الطرق الحرارية والاشعاعية",
                "code" => "452ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "حسابات موزعه",
                "code" => "452رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "امن الحاسب",
                "code" => "453رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "زراعة الانسجة",
                "code" => "454ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "موضوعات مختارة فى علوم الحاسب(2)",
                "code" => "455رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "معمل فيزياء اشباه الموصلات",
                "code" => "455ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "الطرق الاحصائية فى الجيوفيزياء",
                "code" => "456ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "جيولوجيا الآثار",
                "code" => "458ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "نظرية المترجمات",
                "code" => "458رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "علوم المواد النانوية وتطبيقاتها",
                "code" => "458ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "الشبكات العصبية",
                "code" => "459رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "جيولوجيا المياه (1)وجيولوجيا البترول(1)",
                "code" => "460ج",
                "department_id" => 4,
                "description" => ""
            ],
            [
                "name" => "معمل الكترونيات (2)",
                "code" => "461ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "حسابات علمية (2)",
                "code" => "462رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "قياسات فيزيائية باستخدام الحاسب (1)",
                "code" => "462ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "تنقيب البيانات",
                "code" => "464رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "التشفير",
                "code" => "466رك",
                "department_id" => 1,
                "description" => ""
            ],
            [
                "name" => "االاكتينوميسيتات",
                "code" => "472ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "فيزياء الليزر وتطبيقاته",
                "code" => "472ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "موضوعات مختارة في الفيزياء (1)",
                "code" => "491ف",
                "department_id" => 2,
                "description" => ""
            ],
            [
                "name" => "بيئة ميكروبية",
                "code" => "494ن",
                "department_id" => 5,
                "description" => ""
            ],
            [
                "name" => "ميكروبيولوجيا التكافل",
                "code" => "496ن",
                "department_id" => 5,
                "description" => ""
            ]
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
