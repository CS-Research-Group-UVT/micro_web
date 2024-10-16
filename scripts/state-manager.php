<?php
$md_redis = null;
function micro_deploy_initialize_state_manager ($silent = false) {
    global $md_redis;
    try {
        if($md_redis !== null) {
            if(!$silent)
                dispatch_success("State manager has already been initialized.");
            return;
        }
        if(!extension_loaded('redis'))
            throw new Exception("Redis extension is not loaded");
        $md_redis = new Redis();
        
    }catch (Exception $e){
        error_log($e);
        if(!$silent)
            dispatch_error("Redis for PHP is not installed. Please contact your hosting provider.");
        return;
    }
    if(!$silent)
        dispatch_success("State manager has been initialized.");

    global $wpdb;
//    Mark it in the DB!
    try {
        $table_name = $wpdb->prefix . 'microdeploy_settings';

        $already_results = $wpdb->get_results("SELECT * FROM $table_name WHERE name = 'state_manager_initialized'");
        error_log(print_r($already_results, true));
        if(count($already_results) > 0)
            $wpdb->update($table_name, array(
                'value' => 'true'
            ), array(
                'id' => $already_results[0]->id
            ));
        else
            $wpdb->insert($table_name, array(
                'name' => 'state_manager_initialized',
                'value' => 'true'
            ));
    }catch (Exception $e){
        error_log($e);
        if(!$silent)
            dispatch_error("Could not register REST route.");
    }

}

function micro_deploy_set_data_rest($request){
//    error_log(print_r($request, true));
    $data = $request->get_body_params();

    if(key_exists('key', $data) === false || key_exists('value', $data) === false)
        return new WP_REST_RESPONSE([
            'data' => "Missing key or value."
        ], 400);

//    Save in Redis
    micro_deploy_set_data($data['key'], $data['value']);

    return new WP_REST_RESPONSE([
        'data' => "Successfully saved the state."
    ], 200);
}

function micro_deploy_get_data_rest($request){
//    error_log(print_r($request, true));
    $data = $request->get_body_params();

    if(key_exists('key', $data) === false || key_exists('value', $data) === false)
        return new WP_REST_RESPONSE([
            'data' => "Missing key or value."
        ], 400);

//    Save in Redis
    $data = micro_deploy_get_data($data['key'], $data['value']);

    return new WP_REST_RESPONSE([
        'data' => $data
    ], 200);
}


function micro_deploy_retrieve_data ($key) {
    global $md_redis;
    try {
        $value = $md_redis->get($key);
        if(!$value){
            error_log("Requested data is missing: " . $key);
            return false;
        }
        return $value;
    }catch (Exception $e){
        error_log($e);
//        dispatch_error("Could not retrieve data from Redis.");
        return false;
    }
}

function micro_deploy_set_data ($key, $value) {
    global $md_redis;

    try {
        $md_redis->set($key, $value);

        return true;
    }catch (Exception $e){
        error_log($e);
//        dispatch_error("Could not retrieve data from Redis.");
        return false;
    }
}


function micro_deploy_get_data ($key, $value) {
    global $md_redis;

    try {
        $data = $md_redis->get($key);
        if(!$data){
            error_log("Requested data is missing: " . $key);
            return false;
        }
        return $data;
    }catch (Exception $e){
        error_log($e);
//        dispatch_error("Could not retrieve data from Redis.");
        return false;
    }
}

function micro_deploy_remove_state_manager() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'microdeploy_settings';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

//    This means that the user did not ever initialize the state manager.
//    Maybe he / she does not need it!
    if(count($results) === 0) {
        dispatch_error("State manager has not been initialized.");
        return;
    }

    $found = false;
    foreach($results as $result)
        if($result->name === 'state_manager_initialized' && $result->value === 'true') {
            $found = $result;
            break;
        }
    if($found) {
        $wpdb->update($table_name, array(
            'value' => 'false'
        ), array(
            'id' => $found->id
        ));
        dispatch_success("State manager has been removed.");
    }
    else
        dispatch_error("State manager has not been initialized.");

}