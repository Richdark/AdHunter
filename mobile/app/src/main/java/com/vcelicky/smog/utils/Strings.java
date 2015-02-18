package com.vcelicky.smog.utils;

import android.widget.EditText;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

import retrofit.client.Response;

/**
 * Created by jerry on 29. 11. 2014.
 */
public class Strings {

    public static final String SERIALIZED_LIST = "serialized-list.ser";

    public static boolean isValid(final String s){
        return s != null && s.length() > 0;
    }

    public static boolean isValid(final EditText e){
        if(e != null && e.getText() != null)
            return isValid(e.getText().toString());
        return false;
    }

    public static String parseResponse(Response response){
        BufferedReader reader = null;
        StringBuilder sb = new StringBuilder();
        try {
            reader = new BufferedReader(new InputStreamReader(response.getBody().in(), "utf-8"));
            String line;
            while ((line = reader.readLine()) != null)
                sb.append(line);
        } catch (IOException e) {
            e.printStackTrace();
        }
        return sb.substring(0, sb.length());
    }

}
