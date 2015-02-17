package com.vcelicky.smog;

import android.app.Application;

import com.vcelicky.smog.utils.Config;

/**
 * Created by jerry on 18. 2. 2015.
 */
public class AdHunterApp extends Application {

    @Override
    public void onCreate() {
        super.onCreate();
        Config.onInit(this);
    }
}
