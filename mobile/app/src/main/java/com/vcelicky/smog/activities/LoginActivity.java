package com.vcelicky.smog.activities;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.vcelicky.smog.R;
import com.vcelicky.smog.abs.BaseActivity;
import com.vcelicky.smog.utils.Strings;

import java.util.Random;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by jerry on 17. 2. 2015.
 */
public class LoginActivity extends BaseActivity implements Callback<Response> {
    private static final String TAG = "LoginActivity";

    private Button mLoginButton;
    private EditText mEmail, mPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        mLoginButton = (Button) findViewById(R.id.login_button);
        mEmail = (EditText) findViewById(R.id.login_email);
        mPassword = (EditText) findViewById(R.id.login_password);

        mLoginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                loginUser();
            }
        });
    }

    private void loginUser() {
        Random rand = new Random();
        int randInt = rand.nextInt(10000) + 1;
        getServiceInterface().loginUser(mEmail.getText().toString(), mPassword.getText().toString(), String.valueOf(randInt), this);
    }

    @Override
    public void success(Response response, Response response2) {
        final String responseText = Strings.parseResponse(response);
        if(responseText.contains("construction") || responseText.contains("OK")) {
            Intent i = new Intent(LoginActivity.this, CameraActivity.class);
            startActivity(i);
            finish();
        } else {
            toastShort("Pri prihlasovaní nastala chyba. Skontrolujte svoje zadané údaje.");
        }
    }

    @Override
    public void failure(RetrofitError error) {
        toastShort("failure");
    }

}
