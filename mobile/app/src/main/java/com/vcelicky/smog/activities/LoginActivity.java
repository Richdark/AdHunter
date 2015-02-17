package com.vcelicky.smog.activities;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import com.vcelicky.smog.R;
import com.vcelicky.smog.abs.BaseActivity;

/**
 * Created by jerry on 17. 2. 2015.
 */
public class LoginActivity extends BaseActivity {

    private Button mLoginButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        mLoginButton = (Button) findViewById(R.id.login_button);
        mLoginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent(LoginActivity.this, CameraActivity.class);
                startActivity(i);
                finish();
            }
        });
    }
}
