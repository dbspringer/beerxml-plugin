<?php

class BeerXML {

	public $recipes = array();

	function __construct( $xml_loc ) {
		$response = wp_remote_get( $xml_loc );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo "XML not retrieved: $error_message";
			return;
		}

		if ( '404' == $response['response']['code'] ) {
			echo "XML not retrieved: Not found.";
			return;
		}

		libxml_disable_entity_loader();
		libxml_use_internal_errors( true );
		$xml = wp_remote_retrieve_body( $response );
		$xrecipes = simplexml_load_string( $xml );
		if ( ! $xrecipes )
			return;

		foreach ( $xrecipes->RECIPE as $recipe ) {
			$this->recipes[] = new BeerXML_Recipe( $recipe );
		}
	}
}

class BeerXML_Recipe {

	public $name;
	public $version;
	public $type;
	public $style;
	public $equipment;
	public $brewer;
	public $asst_brewer;
	public $batch_size;
	public $boil_size;
	public $boil_time;
	public $efficiency;
	public $hops = array();
	public $fermentables = array();
	public $miscs = array();
	public $yeasts = array();
	public $waters = array();
	public $mash;
	public $notes;
	public $taste_notes;
	public $taste_rating;
	public $og;
	public $fg;
	public $fermentation_stages;
	public $primary_age;
	public $primary_temp;
	public $secondary_age;
	public $secondary_temp;
	public $tertiary_age;
	public $tertiary_temp;
	public $age;
	public $age_temp;
	public $date;
	public $carbonation;
	public $forced_carbonation;
	public $priming_sugar_name;
	public $carbonation_temp;
	public $priming_sugar_equiv;
	public $keg_priming_factor;
	public $est_og;
	public $est_fg;
	public $est_color;
	public $ibu;
	public $ibus;
	public $ibu_method;
	public $est_abv;
	public $abv;
	public $actual_efficiency;
	public $calories;
	public $display_batch_size;
	public $display_boil_size;
	public $display_og;
	public $display_fg;
	public $display_primary_temp;
	public $display_secondary_temp;
	public $display_tertiary_temp;
	public $display_age_temp;
	public $carbonation_used;
	public $display_carb_temp;

	function __construct( $recipe ) {
		$skip = array( 'HOPS', 'FERMENTABLES', 'MISCS', 'YEASTS', 'WATERS' );

		if( $recipe->HOPS->HOP ) {
			foreach ( $recipe->HOPS->HOP as $hop ) {
				$this->hops[] = new BeerXML_Hop( $hop );
			}
		}

		if( $recipe->FERMENTABLES->FERMENTABLE ) {
			foreach ( $recipe->FERMENTABLES->FERMENTABLE as $fermentable ) {
				$this->fermentables[] = new BeerXML_Fermentable( $fermentable );
			}
		}

		if( $recipe->MISCS->MISC ) {
			foreach ( $recipe->MISCS->MISC as $misc ) {
				$this->miscs[] = new BeerXML_Misc( $misc );
			}
		}

		if( $recipe->YEASTS->YEAST ) {
			foreach ( $recipe->YEASTS->YEAST as $yeast ) {
				$this->yeasts[] = new BeerXML_Yeast( $yeast );
			}
		}

		if( $recipe->WATERS->WATER ) {
			foreach ( $recipe->WATERS->WATER as $water ) {
				$this->waters[] = new BeerXML_Water( $water );
			}
		}

		foreach ( $recipe as $k => $v ) {
			if ( in_array( $k, $skip ) ) {
				continue;
			} else if ( 'STYLE' == $k ) {
				$this->{strtolower( $k )} = new BeerXML_Style( $v );
			} else if ( 'EQUIPMENT' == $k ) {
				$this->{strtolower( $k )} = new BeerXML_Equipment( $v );
			} else if ( 'MASH' == $k ) {
				$this->{strtolower( $k )} = new BeerXML_Mash_Profile( $v );
			} else {
				$this->{strtolower( $k )} = esc_html( (string)$v );
			}
		}
	}
}

class BeerXML_Hop {

	public $name;
	public $version;
	public $alpha;
	public $amount;
	public $use;
	public $time;
	public $notes;
	public $type;
	public $form;
	public $beta;
	public $hsi;
	public $origin;
	public $substitutes;
	public $humulene;
	public $caryophyllene;
	public $cohumulone;
	public $myrcene;

	function __construct( $hop ) {
		foreach ( $hop as $k => $v ) {
			$this->{strtolower( $k )} = esc_html( (string)$v );
		}
	}
}


class BeerXML_Fermentable {

	public $name;
	public $version;
	public $type;
	public $amount;
	public $yield;
	public $color;
	public $add_after_boil;
	public $origin;
	public $supplier;
	public $notes;
	public $coarse_fine_diff;
	public $moisture;
	public $diastatic_power;
	public $protein;
	public $max_in_batch;
	public $recommend_mash;

	function __construct( $fermentable ) {
		foreach ( $fermentable as $k => $v ) {
			$this->{strtolower( $k )} = esc_html( (string)$v );
		}
	}

	public static function calculate_total( array $fermentables ) {
		$total = 0;
		foreach ( $fermentables as $fermentable ) {
			$total += $fermentable->amount;
		}

		return $total;
	}

	public function percentage( $total ) {
		return ( $this->amount / $total ) * 100;
	}
}


class BeerXML_Yeast {

	public $name;
	public $version;
	public $type;
	public $form;
	public $amount;
	public $amount_is_weight;
	public $laboratory;
	public $product_id;
	public $min_temperature;
	public $max_temperature;
	public $flocculation;
	public $attenuation;
	public $notes;
	public $best_for;
	public $times_cultured;
	public $max_reuse;
	public $add_to_secondary;

	function __construct( $yeast ) {
		foreach ( $yeast as $k => $v ) {
			$this->{strtolower( $k )} = esc_html( (string)$v );
		}
	}
}


class BeerXML_Misc {

	public $name;
	public $version;
	public $type;
	public $use;
	public $time;
	public $amount;
	public $amount_is_weight;
	public $use_for;
	public $notes;
	public $water;

	function __construct( $misc ) {
		foreach ( $misc as $k => $v ) {
			$this->{strtolower( $k )} = esc_html( (string)$v );
		}
	}
}


class BeerXML_Water {

	public $name;
	public $version;
	public $amount;
	public $calcium;
	public $bicarbonate;
	public $sulfate;
	public $chloride;
	public $sodium;
	public $magnesium;
	public $ph;
	public $notes;

	function __construct( $water ) {
		foreach ( $water as $k => $v ) {
			$this->{strtolower( $k )} = esc_html( (string)$v );
		}
	}
}


class BeerXML_Equipment {

	public $name;
	public $version;
	public $boil_size;
	public $batch_size;
	public $tun_volume;
	public $tun_weight;
	public $tun_specific_heat;
	public $top_up_water;
	public $trub_chiller_loss;
	public $evap_rate;
	public $boil_time;
	public $calc_boil_volume;
	public $lauter_deadspace;
	public $top_up_kettle;
	public $hop_utilization;
	public $notes;

	function __construct( $equipment ) {
		foreach ( $equipment as $k => $v ) {
			$this->{strtolower( $k )} = esc_html( (string)$v );
		}
	}
}


class BeerXML_Style {

	public $name;
	public $category;
	public $version;
	public $category_number;
	public $style_letter;
	public $style_guide;
	public $type;
	public $og_min;
	public $og_max;
	public $fg_min;
	public $fg_max;
	public $ibu_min;
	public $ibu_max;
	public $color_min;
	public $color_max;
	public $carb_min;
	public $carb_max;
	public $abv_min;
	public $abv_max;
	public $notes;
	public $profile;
	public $ingredients;
	public $examples;

	function __construct( $style ) {
		foreach ( $style as $k => $v ) {
			$this->{strtolower( $k )} = esc_html( (string)$v );
		}
	}
}


class BeerXML_Mash_Step {

	public $name;
	public $version;
	public $type;
	public $infuse_amount;
	public $step_temp;
	public $step_time;
	public $ramp_time;
	public $end_temp;

	function __construct( $mash_step ) {
		foreach ( $mash_step as $k => $v ) {
			$this->{strtolower( $k )} = esc_html( (string)$v );
		}
	}
}


class BeerXML_Mash_Profile {

	public $name;
	public $version;
	public $grain_temp;
	public $mash_steps = array();
	public $notes;
	public $tun_temp;
	public $sparge_temp;
	public $ph;
	public $tun_weight;
	public $tun_specific_heat;
	public $equip_adjust;

	function __construct( $mash_profile ) {
		if ( $mash_profile->MASH_STEPS->MASH_STEP ) {
			foreach ( $mash_profile->MASH_STEPS->MASH_STEP as $mash_step ) {
				$this->mash_steps[] = new BeerXML_Mash_Step( $mash_step );
			}
		}

		foreach ( $mash_profile as $k => $v ) {
			if ( 'MASH_STEPS' != $k ) {
				$this->{strtolower( $k )} = esc_html( (string)$v );
			}
		}
	}
}

if ( ! function_exists( 'url_exists' ) ) :

function url_exists( $url ) {
	$file_headers = @get_headers( $url );
	return false === strpos( $file_headers[0], '404' );;
}

endif;
