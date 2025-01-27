<?php
class Validator
{
    public function validate($params, $validation_rules)
    {
        try {
            $response = '';

            foreach ($validation_rules as $field => $rules) {
                foreach (explode('|', $rules) as $rule) {
                    if ($rule == 'required' && array_key_exists($field, $params) == false) {
                        $response .= "The " . $field ." is required.\n";
                    }

                    if (array_key_exists($field, $params) == true) {
                        if ($rule == 'alphanumeric' && preg_match('/^[a-z0-9 .\-]+$/i', $params[$field]) == false) {
                            $response .= "The value of " . $field . " is not a valid alphanumeric value.\n";
                        }

                        if ($rule == 'phone' && preg_match('/^[0-9 \-\(\)\+]+$/i', $params[$field]) == false) {
                            $response .= "The value of " . $field . " is not a valid phone number.\n";
                        }

                        if ($rule == 'date' && strtotime($params[$field]) === false) {
                            $response .= "The value of " . $field . " is not a valid date.\n";
                        }

                        // Add age validation
                        if ($rule == 'age' && strtotime($params[$field]) !== false) {
                            $dob = new DateTime($params[$field]);
                            $today = new DateTime();
                            $age = $today->diff($dob)->y;
                            if ($age < 18 || $age > 60) {
                                $response .= "Age must be between 18 and 60 years.\n";
                            }
                        }
                    }
                }
            }

            return $response;

        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}