<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self student()
 * @method static self school_of_medicine()
 * @method static self school_of_pharmacy()
 * @method static self school_of_nursing()
 * @method static self school_of_veterinary_medicine()
 * @method static self school_of_law()
 * @method static self school_of_engineering()
 * @method static self school_of_science()
 * @method static self school_of_business()
 * @method static self school_of_language_and_literature()
 * @method static self school_of_education()
 * @method static self school_of_music()
 * @method static self school_of_arts()
 * @method static self school_of_sports()
 * @method static self military_academy()
 * @method static self school_of_other_categories()
 *
 * @method static self entrepreneur()
 * @method static self startup()
 * @method static self venture_capital()
 * @method static self real_estate_investment()
 * @method static self franchise()
 * @method static self small_business()
 * @method static self freelancer()
 * @method static self other_entrepreneur()
 *
 * @method static self medical()
 * @method static self doctor()
 * @method static self oriental_doctor()
 * @method static self pharmacist()
 * @method static self oriental_pharmacist()
 * @method static self vet()
 * @method static self nurse()
 * @method static self other_medical()
 *
 * @method static self legal()
 * @method static self judge()
 * @method static self prosecutor()
 * @method static self lawyer()
 * @method static self other_legal()
 *
 * @method static self high_paying_profession()
 * @method static self public_appraiser()
 * @method static self architect()
 * @method static self management_consultant()
 * @method static self fund_manager()
 * @method static self labor_consultant()
 * @method static self public_accountant()
 * @method static self customs_agent()
 * @method static self judicial_scrivener()
 * @method static self patent_attorney()
 * @method static self actuary()
 * @method static self tax_accountant()
 * @method static self claim_adjuster()
 * @method static self financial_planner()
 * @method static self pilot()
 * @method static self flight_attendant()
 * @method static self flight_engineer()
 * @method static self air_traffic_controller()
 * @method static self surveyor()
 * @method static self public_attorney()
 * @method static self other_high_paying_profession()
 *
 * @method static self financial()
 * @method static self bank()
 * @method static self securities()
 * @method static self insurance()
 * @method static self accounting()
 * @method static self other_financial()
 *
 * @method static self research()
 * @method static self it_researcher()
 * @method static self semiconductor_researcher()
 * @method static self science_researcher()
 * @method static self chemical_researcher()
 * @method static self mechanical_researcher()
 * @method static self bio_researcher()
 * @method static self other_researcher()
 *
 * @method static self engineer()
 * @method static self software_engineer()
 * @method static self hardware_engineer()
 * @method static self architectual_engineer()
 * @method static self electrical_engineer()
 * @method static self marine_engineer()
 * @method static self mechanical_engineer()
 * @method static self other_engineer()
 *
 * @method static self design()
 * @method static self graphic_designer()
 * @method static self ui_ux_designer()
 * @method static self fashion_designer()
 * @method static self interior_designer()
 * @method static self product_designer()
 * @method static self other_designer()
 *
 * @method static self teaching()
 * @method static self kindergarten_teacher()
 * @method static self school_teacher()
 * @method static self professor()
 * @method static self academy_instructor()
 * @method static self tutor()
 * @method static self other_teaching()
 *
 * @method static self media()
 * @method static self journalist()
 * @method static self journal_editor()
 * @method static self broadcasting_writer()
 * @method static self actor()
 * @method static self comedian()
 * @method static self voice_actor()
 * @method static self model()
 * @method static self announcer()
 * @method static self other_media()
 *
 * @method static self entertainment()
 * @method static self entertainment_manager()
 * @method static self e_sports_player()
 * @method static self streamer()
 * @method static self influencer()
 * @method static self other_entertainment()
 *
 * @method static self sports()
 * @method static self coach()
 * @method static self sports_instructor()
 * @method static self athlete()
 * @method static self fitness_trainer()
 * @method static self other_sports()
 *
 * @method static self arts()
 * @method static self vocalist()
 * @method static self musician()
 * @method static self dancer()
 * @method static self painter()
 * @method static self sculptor()
 * @method static self other_arts()
 *
 * @method static self service()
 * @method static self customer_service()
 * @method static self language_service()
 * @method static self beauty_service()
 * @method static self environmental_services()
 * @method static self pet_service()
 * @method static self security_service()
 * @method static self other_service()
 *
 * @method static self government()
 * @method static self government_official()
 * @method static self foreign_service()
 * @method static self police_force()
 * @method static self fire_service()
 * @method static self military_service()
 * @method static self soldier()
 * @method static self other_government()
 *
 * @method static self other()
 * @method static self sales()
 * @method static self retail()
 * @method static self farming()
 * @method static self fishery()
 * @method static self between_jobs()
 * @method static self none()
 *
 */
class JobEnum extends Enum
{
//    protected static function values(): array
//    {
//        return [
//            'student' => 2,
//            'school_of_medicine' => 3,
//            'school_of_pharmacy' => 4,
//            'school_of_nursing' => 5,
//            'school_of_veterinary_medicine' => 6,
//            'school_of_law' => 7,
//            'school_of_engineering' => 8,
//            'school_of_science' => 9,
//            'school_of_business' => 10,
//            'school_of_language_and_literature' => 11,
//            'school_of_education' => 12,
//            'school_of_music' => 13,
//            'school_of_arts' => 14,
//            'school_of_sports' => 15,
//            'military_academy' => 16,
//            'school_of_other_categories' => 17,
//            'entrepreneur' => 18,
//            'startup' => 19,
//            'venture_capital' => 20,
//            'real_estate_investment' => 21,
//            'franchise' => 22,
//            'small_business' => 23,
//            'freelancer' => 24,
//            'other_entrepreneur' => 25,
//            'medical' => 26,
//            'doctor' => 27,
//            'oriental_doctor' => 28,
//            'pharmacist' => 29,
//            'oriental_pharmacist' => 30,
//            'vet' => 31,
//            'nurse' => 32,
//            'other_medical' => 33,
//            'legal' => 34,
//            'judge' => 35,
//            'prosecutor' => 36,
//            'lawyer' => 37,
//            'other_legal' => 38,
//            'high_paying_profession' => 39,
//            'public_appraiser' => 40,
//            'architect' => 41,
//            'management_consultant' => 42,
//            'fund_manager' => 43,
//            'labor_consultant' => 44,
//            'public_accountant' => 45,
//            'customs_agent' => 46,
//            'judicial_scrivener' => 47,
//            'patent_attorney' => 48,
//            'actuary' => 49,
//            'tax_accountant' => 50,
//            'claim_adjuster' => 51,
//            'financial_planner' => 52,
//            'pilot' => 53,
//            'flight_attendant' => 54,
//            'flight_engineer' => 55,
//            'air_traffic_controller' => 56,
//            'surveyor' => 57,
//            'public_attorney' => 58,
//            'other_high_paying_profession' => 59,
//            'financial' => 60,
//            'bank' => 61,
//            'securities' => 62,
//            'insurance' => 63,
//            'accounting' => 64,
//            'other_financial' => 65,
//            'research' => 66,
//            'it_researcher' => 67,
//            'semiconductor_researcher' => 68,
//            'science_researcher' => 69,
//            'chemical_researcher' => 70,
//            'mechanical_researcher' => 71,
//            'bio_researcher' => 72,
//            'other_researcher' => 73,
//            'engineer' => 74,
//            'software_engineer' => 75,
//            'hardware_engineer' => 76,
//            'architectual_engineer' => 77,
//            'electrical_engineer' => 78,
//            'marine_engineer' => 79,
//            'mechanical_engineer' => 80,
//            'other_engineer' => 81,
//            'design' => 82,
//            'graphic_designer' => 83,
//            'ui_ux_designer' => 84,
//            'fashion_designer' => 85,
//            'interior_designer' => 86,
//            'product_designer' => 87,
//            'other_designer' => 88,
//            'teaching' => 89,
//            'kindergarten_teacher' => 90,
//            'school_teacher' => 91,
//            'professor' => 92,
//            'academy_instructor' => 93,
//            'tutor' => 94,
//            'other_teaching' => 95,
//            'media' => 96,
//            'journalist' => 97,
//            'journal_editor' => 98,
//            'broadcasting_writer' => 99,
//            'actor' => 100,
//            'comedian' => 101,
//            'voice_actor' => 102,
//            'model' => 103,
//            'announcer' => 104,
//            'other_media' => 105,
//            'entertainment' => 106,
//            'entertainment_manager' => 107,
//            'e_sports_player' => 108,
//            'streamer' => 109,
//            'influencer' => 110,
//            'other_entertainment' => 111,
//            'sports' => 112,
//            'coach' => 113,
//            'sports_instructor' => 114,
//            'athlete' => 115,
//            'fitness_trainer' => 116,
//            'other_sports' => 117,
//            'arts' => 118,
//            'vocalist' => 119,
//            'musician' => 120,
//            'dancer' => 121,
//            'painter' => 122,
//            'sculptor' => 123,
//            'other_arts' => 124,
//            'service' => 125,
//            'customer_service' => 126,
//            'language_service' => 127,
//            'beauty_service' => 128,
//            'environmental_services' => 129,
//            'pet_service' => 130,
//            'security_service' => 131,
//            'other_service' => 132,
//            'government' => 133,
//            'government_official' => 134,
//            'foreign_service' => 135,
//            'police_force' => 136,
//            'fire_service' => 137,
//            'military_service' => 138,
//            'soldier' => 139,
//            'other_government' => 140,
//            'other' => 141,
//            'sales' => 142,
//            'retail' => 143,
//            'farming' => 144,
//            'fishery' => 145,
//            'between_jobs' => 146,
//            'none' => 147,
//        ];
//    }
}
