package sk.fiit.adhunter.utils;

import android.content.Context;
import android.provider.Settings;

import retrofit.RestAdapter;

/**
 * Created by jerry on 17. 2. 2015.
 */
public class Config {

//    public static final String ENDPOINT_URL = "http://team14-14.ucebne.fiit.stuba.sk/adhunter"; // test
    public static final String ENDPOINT_URL = "http://adhunter.eu";
    public static final RestAdapter.LogLevel LOG_LEVEL = RestAdapter.LogLevel.FULL;
    public static String DEVICE_ID;

    public static void onInit(Context context) {
        DEVICE_ID = Settings.Secure.getString(context.getContentResolver(), Settings.Secure.ANDROID_ID);
    }

}
