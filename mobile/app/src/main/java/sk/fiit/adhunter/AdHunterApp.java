package sk.fiit.adhunter;

import android.app.Application;

import sk.fiit.adhunter.utils.Config;

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
