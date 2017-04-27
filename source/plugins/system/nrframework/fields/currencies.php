<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2017 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldNR_Currencies extends JFormFieldList
{
	protected $type = 'nr_currencies';

	protected $currencies = array(
		"AED" => "United Arab Emirates Dirham",
		"AFN" => "Afghan Afghani",
		"ALL" => "Albanian Lek",
		"AMD" => "Armenian Dram",
		"ANG" => "Netherlands Antillean Guilder",
		"AOA" => "Angolan Kwanza",
		"ARS" => "Argentine Peso",
		"AUD" => "Australian Dollar",
		"AWG" => "Aruban Florin",
		"AZN" => "Azerbaijani Manat",
		"BAM" => "Bosnia-Herzegovina Convertible Mark",
		"BBD" => "Barbadian Dollar",
		"BDT" => "Bangladeshi Taka",
		"BGN" => "Bulgarian Lev",
		"BHD" => "Bahraini Dinar",
		"BIF" => "Burundian Franc",
		"BMD" => "Bermudan Dollar",
		"BND" => "Brunei Dollar",
		"BOB" => "Bolivian Boliviano",
		"BRL" => "Brazilian Real",
		"BSD" => "Bahamian Dollar",
		"BTC" => "Bitcoin",
		"BTN" => "Bhutanese Ngultrum",
		"BWP" => "Botswanan Pula",
		"BYN" => "Belarusian Ruble",
		"BYR" => "Belarusian Ruble (pre-2016)",
		"BZD" => "Belize Dollar",
		"CAD" => "Canadian Dollar",
		"CDF" => "Congolese Franc",
		"CHF" => "Swiss Franc",
		"CLF" => "Chilean Unit of Account (UF)",
		"CLP" => "Chilean Peso",
		"CNY" => "Chinese Yuan",
		"COP" => "Colombian Peso",
		"CRC" => "Costa Rican Colón",
		"CUC" => "Cuban Convertible Peso",
		"CUP" => "Cuban Peso",
		"CVE" => "Cape Verdean Escudo",
		"CZK" => "Czech Republic Koruna",
		"DJF" => "Djiboutian Franc",
		"DKK" => "Danish Krone",
		"DOP" => "Dominican Peso",
		"DZD" => "Algerian Dinar",
		"EEK" => "Estonian Kroon",
		"EGP" => "Egyptian Pound",
		"ERN" => "Eritrean Nakfa",
		"ETB" => "Ethiopian Birr",
		"EUR" => "Euro",
		"FJD" => "Fijian Dollar",
		"FKP" => "Falkland Islands Pound",
		"GBP" => "British Pound Sterling",
		"GEL" => "Georgian Lari",
		"GGP" => "Guernsey Pound",
		"GHS" => "Ghanaian Cedi",
		"GIP" => "Gibraltar Pound",
		"GMD" => "Gambian Dalasi",
		"GNF" => "Guinean Franc",
		"GTQ" => "Guatemalan Quetzal",
		"GYD" => "Guyanaese Dollar",
		"HKD" => "Hong Kong Dollar",
		"HNL" => "Honduran Lempira",
		"HRK" => "Croatian Kuna",
		"HTG" => "Haitian Gourde",
		"HUF" => "Hungarian Forint",
		"IDR" => "Indonesian Rupiah",
		"ILS" => "Israeli New Sheqel",
		"IMP" => "Manx pound",
		"INR" => "Indian Rupee",
		"IQD" => "Iraqi Dinar",
		"IRR" => "Iranian Rial",
		"ISK" => "Icelandic Króna",
		"JEP" => "Jersey Pound",
		"JMD" => "Jamaican Dollar",
		"JOD" => "Jordanian Dinar",
		"JPY" => "Japanese Yen",
		"KES" => "Kenyan Shilling",
		"KGS" => "Kyrgystani Som",
		"KHR" => "Cambodian Riel",
		"KMF" => "Comorian Franc",
		"KPW" => "North Korean Won",
		"KRW" => "South Korean Won",
		"KWD" => "Kuwaiti Dinar",
		"KYD" => "Cayman Islands Dollar",
		"KZT" => "Kazakhstani Tenge",
		"LAK" => "Laotian Kip",
		"LBP" => "Lebanese Pound",
		"LKR" => "Sri Lankan Rupee",
		"LRD" => "Liberian Dollar",
		"LSL" => "Lesotho Loti",
		"LTL" => "Lithuanian Litas",
		"LVL" => "Latvian Lats",
		"LYD" => "Libyan Dinar",
		"MAD" => "Moroccan Dirham",
		"MDL" => "Moldovan Leu",
		"MGA" => "Malagasy Ariary",
		"MKD" => "Macedonian Denar",
		"MMK" => "Myanma Kyat",
		"MNT" => "Mongolian Tugrik",
		"MOP" => "Macanese Pataca",
		"MRO" => "Mauritanian Ouguiya",
		"MTL" => "Maltese Lira",
		"MUR" => "Mauritian Rupee",
		"MVR" => "Maldivian Rufiyaa",
		"MWK" => "Malawian Kwacha",
		"MXN" => "Mexican Peso",
		"MYR" => "Malaysian Ringgit",
		"MZN" => "Mozambican Metical",
		"NAD" => "Namibian Dollar",
		"NGN" => "Nigerian Naira",
		"NIO" => "Nicaraguan Córdoba",
		"NOK" => "Norwegian Krone",
		"NPR" => "Nepalese Rupee",
		"NZD" => "New Zealand Dollar",
		"OMR" => "Omani Rial",
		"PAB" => "Panamanian Balboa",
		"PEN" => "Peruvian Nuevo Sol",
		"PGK" => "Papua New Guinean Kina",
		"PHP" => "Philippine Peso",
		"PKR" => "Pakistani Rupee",
		"PLN" => "Polish Zloty",
		"PYG" => "Paraguayan Guarani",
		"QAR" => "Qatari Rial",
		"RON" => "Romanian Leu",
		"RSD" => "Serbian Dinar",
		"RUB" => "Russian Ruble",
		"RWF" => "Rwandan Franc",
		"SAR" => "Saudi Riyal",
		"SBD" => "Solomon Islands Dollar",
		"SCR" => "Seychellois Rupee",
		"SDG" => "Sudanese Pound",
		"SEK" => "Swedish Krona",
		"SGD" => "Singapore Dollar",
		"SHP" => "Saint Helena Pound",
		"SLL" => "Sierra Leonean Leone",
		"SOS" => "Somali Shilling",
		"SRD" => "Surinamese Dollar",
		"STD" => "São Tomé and Príncipe Dobra",
		"SVC" => "Salvadoran Colón",
		"SYP" => "Syrian Pound",
		"SZL" => "Swazi Lilangeni",
		"THB" => "Thai Baht",
		"TJS" => "Tajikistani Somoni",
		"TMT" => "Turkmenistani Manat",
		"TND" => "Tunisian Dinar",
		"TOP" => "Tongan Pa?anga",
		"TRY" => "Turkish Lira",
		"TTD" => "Trinidad and Tobago Dollar",
		"TWD" => "New Taiwan Dollar",
		"TZS" => "Tanzanian Shilling",
		"UAH" => "Ukrainian Hryvnia",
		"UGX" => "Ugandan Shilling",
		"USD" => "United States Dollar",
		"UYU" => "Uruguayan Peso",
		"UZS" => "Uzbekistan Som",
		"VEF" => "Venezuelan Bolívar Fuerte",
		"VND" => "Vietnamese Dong",
		"VUV" => "Vanuatu Vatu",
		"WST" => "Samoan Tala",
		"XAF" => "CFA Franc BEAC",
		"XAG" => "Silver Ounce",
		"XAU" => "Gold Ounce",
		"XCD" => "East Caribbean Dollar",
		"XDR" => "Special Drawing Rights",
		"XOF" => "CFA Franc BCEAO",
		"XPD" => "Palladium Ounce",
		"XPF" => "CFP Franc",
		"XPT" => "Platinum Ounce",
		"YER" => "Yemeni Rial",
		"ZAR" => "South African Rand",
		"ZMK" => "Zambian Kwacha (pre-2013)",
		"ZMW" => "Zambian Kwacha",
		"ZWL" => "Zimbabwean Dollar",
	);

	protected function getOptions()
	{
		$options = array();
		$options[] = JHTML::_('select.option', "", "- " . JText::_("NR_SELECT_CURRENCY"). " -");

		asort($this->currencies);

		foreach ($this->currencies as $key => $value)
		{
			$options[] = JHTML::_('select.option', $key, $key . " (" . $value . ")");
		}

		return array_merge(parent::getOptions(), $options);
	}
}