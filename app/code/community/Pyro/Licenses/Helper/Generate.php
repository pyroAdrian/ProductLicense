<?php
/**
 * Licenses Generator helper
 *
 * @author Pyro
 */
class Pyro_Licenses_Helper_Generate extends Mage_Core_Helper_Data
{
    /**
     * Generate license key
     * 
     * @return string
     */
    public function license()
    {
        $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $segment_chars = 5;
        $num_segments = 4;
        $key_string = '';
    
        for ($i = 0; $i < $num_segments; $i++) {
            $segment = '';

            for ($j = 0; $j < $segment_chars; $j++) {
                $segment .= $tokens[rand(0, 35)];
            }
    
            $key_string .= $segment;
    
            if ($i < ($num_segments - 1)) {
                $key_string .= '-';
            }
        }
    
        return $key_string;
    }
}