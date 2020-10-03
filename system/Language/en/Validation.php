<?php

/**
 * Validation language strings.
 *
 * @package    CodeIgniter
 * @author     CodeIgniter Dev Team
 * @copyright  2019-2020 CodeIgniter Foundation
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       https://codeigniter.com
 * @since      Version 4.0.0
 * @filesource
 *
 * @codeCoverageIgnore
 */

return [
	// Core Messages
   'noRuleSets'            => 'No rulesets specified in Validation configuration.',
   'ruleNotFound'          => '{0} is not a valid rule.',
   'groupNotFound'         => '{0} is not a validation rules group.',
   'groupNotArray'         => '{0} rule group must be an array.',
   'invalidTemplate'       => '{0} is not a valid Validation template.',

	// Rule Messages
   'alpha'                 => 'El "{field}" field may only contain alphabetical characters.',
   'alpha_dash'            => 'El "{field}" field may only contain alphanumeric, underscore, and dash characters.',
   'alpha_numeric'         => 'El "{field}" field may only contain alphanumeric characters.',
   'alpha_numeric_punct'   => 'El "{field}" field may contain only alphanumeric characters, spaces, and  ~ ! # $ % & * - _ + = | : . characters.',
   'alpha_numeric_space'   => 'El "{field}" field may only contain alphanumeric and space characters.',
   'alpha_space'           => 'El "{field}" field may only contain alphabetical characters and spaces.',
   'decimal'               => 'El "{field}" field must contain a decimal number.',
   'differs'               => 'El "{field}" field must differ from the {param} field.',
   'equals'                => 'El "{field}" field must be exactly: {param}.',
   'exact_length'          => 'El "{field}" field must be exactly {param} characters in length.',
   'greater_than'          => 'El "{field}" field must contain a number greater than {param}.',
   'greater_than_equal_to' => 'El "{field}" field must contain a number greater than or equal to {param}.',
   'hex'                   => 'El "{field}" field may only contain hexidecimal characters.',
   'in_list'               => 'El "{field}" field must be one of: {param}.',
   'integer'               => 'El "{field}" field must contain an integer.',
   'is_natural'            => 'El "{field}" field must only contain digits.',
   'is_natural_no_zero'    => 'El "{field}" field must only contain digits and must be greater than zero.',
   'is_not_unique'         => 'El "{field}" field must contain a previously existing value in the database.',
   'is_unique'             => 'El campo "{field}" debe contener un valor unico.',
   'less_than'             => 'El "{field}" field must contain a number less than {param}.',
   'less_than_equal_to'    => 'El "{field}" field must contain a number less than or equal to {param}.',
   'matches'               => 'El "{field}" field does not match the {param} field.',
   'max_length'            => 'El "{field}" field cannot exceed {param} characters in length.',
   'min_length'            => 'El "{field}" field must be at least {param} characters in length.',
   'not_equals'            => 'El "{field}" field cannot be: {param}.',
   'numeric'               => 'El "{field}" field must contain only numbers.',
   'regex_match'           => 'El "{field}" field is not in the correct format.',
   'required'              => 'El campo "{field}" es requerido.',
   'required_with'         => 'El campo "{field}" es requerido when {param} is present.',
   'required_without'      => 'El campo "{field}" es requerido when {param} is not present.',
   'string'                => 'El "{field}" field must be a valid string.',
   'timezone'              => 'El "{field}" field must be a valid timezone.',
   'valid_base64'          => 'El "{field}" field must be a valid base64 string.',
   'valid_email'           => 'El "{field}" field must contain a valid email address.',
   'valid_emails'          => 'El "{field}" field must contain all valid email addresses.',
   'valid_ip'              => 'El "{field}" field must contain a valid IP.',
   'valid_url'             => 'El "{field}" field must contain a valid URL.',
   'valid_date'            => 'El "{field}" field must contain a valid date.',

	// Credit Cards
   'valid_cc_num'          => '"{field}" does not appear to be a valid credit card number.',

	// Files
   'uploaded'              => '"{field}" is not a valid uploaded file.',
   'max_size'              => '"{field}" is too large of a file.',
   'is_image'              => '"{field}" is not a valid, uploaded image file.',
   'mime_in'               => '"{field}" does not have a valid mime type.',
   'ext_in'                => '"{field}" does not have a valid file extension.',
   'max_dims'              => '"{field}" is either not an image, or it is too wide or tall.',
];
