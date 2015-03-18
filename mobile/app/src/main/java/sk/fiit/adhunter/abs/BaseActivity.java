package sk.fiit.adhunter.abs;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentSender;
import android.location.Location;
import android.location.LocationManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.provider.Settings;
import android.text.format.DateUtils;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesClient;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationClient;
import com.google.android.gms.location.LocationListener;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationServices;

import sk.fiit.adhunter.R;
import sk.fiit.adhunter.models.Photo;
import sk.fiit.adhunter.services.ServiceInterface;
import sk.fiit.adhunter.utils.Config;
import sk.fiit.adhunter.utils.SerializationUtils;
import sk.fiit.adhunter.utils.Strings;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import retrofit.ErrorHandler;
import retrofit.RequestInterceptor;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Jerry on 10. 10. 2014.
 * Activity that all the other activities inherit from.
 */
public class BaseActivity extends Activity implements GoogleApiClient.ConnectionCallbacks,
        GoogleApiClient.OnConnectionFailedListener,
        LocationListener, RequestInterceptor, ErrorHandler {

    private static final String TAG = "BaseActivity";

    private LocationClient mLocationClient;
    private LocationRequest mLocationRequest;
    protected LocationManager mLocationManager;
    protected Location mLocation;
    private GoogleApiClient mGoogleApiClient;
    protected ServiceInterface mServiceInterface;
    protected boolean isFirstKnownLocation;
    protected long mFirstTime, mSecondTime;
    protected String mTimeDifference = "";

    private final static int CONNECTION_FAILURE_RESOLUTION_REQUEST = 9000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        RestAdapter restAdapter = new RestAdapter.Builder()
                .setLogLevel(Config.LOG_LEVEL)
                .setEndpoint(Config.ENDPOINT_URL)
                .setRequestInterceptor(this)
                .setErrorHandler(this)
                .build();

        mServiceInterface = restAdapter.create(ServiceInterface.class);

        mLocationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        isFirstKnownLocation = true; // first known location is usually old and wrong
        buildGoogleApiClient();

        mLocationRequest = LocationRequest.create()
                .setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY)
                .setInterval(1000)        // 1 seconds, in milliseconds
                .setFastestInterval(1000); // 1 second, in milliseconds
    }

    protected synchronized void buildGoogleApiClient() {
        mGoogleApiClient = new GoogleApiClient.Builder(this)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .addApi(LocationServices.API)
                .build();
    }

    @Override
    protected void onResume() {
        super.onResume();

        if (mGoogleApiClient != null) {
            mGoogleApiClient.connect();
        }
    }

    @Override
    protected void onPause() {
        super.onPause();

        if (mGoogleApiClient != null && mGoogleApiClient.isConnected()) {
            LocationServices.FusedLocationApi.removeLocationUpdates(mGoogleApiClient, this);
            mGoogleApiClient.disconnect();
        }
    }

    @Override
    protected void onStart() {
        super.onStart();
        if (mLocationClient != null)
            mLocationClient.connect();
    }

    @Override
    protected void onStop() {
        if (mLocationClient != null && mLocationClient.isConnected()) {
            mLocationClient.removeLocationUpdates(this);
            mLocationClient.disconnect();
        }
        super.onStop();
    }

    public ServiceInterface getServiceInterface() {
        return mServiceInterface;
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.base, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == R.id.action_settings) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    /**
     *
     * @param context Context of calling activity.
     * @return true if WiFi is enabled and connected, otherwise false
     */
    public static boolean isWiFiConnected(Context context) {
        ConnectivityManager cm =
                (ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE);

        NetworkInfo activeNetwork = cm.getActiveNetworkInfo();
        boolean isConnected = activeNetwork != null &&
                activeNetwork.isConnected();
        boolean isWiFi = false;
        if(isConnected) {
            // activeNetwork.getType() should return 1 when WiFi is on
            isWiFi = activeNetwork.getType() == ConnectivityManager.TYPE_WIFI;
        }
        return isWiFi;
    }

    /**
     * Checks current connectivity status of device.
     *
     * @param context Context of calling activity.
     * @return true if WiFi or Mobile internet connection is established, false if not
     */
    public boolean isWifiOrMobileConnected(Context context) {
        ConnectivityManager cm =
                (ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE);

        NetworkInfo activeNetwork = cm.getActiveNetworkInfo();
        boolean isConnected = activeNetwork != null &&
                activeNetwork.isConnected();
        boolean isWiFiOrMobile = false;
        if(isConnected) {
            //pri mobilnu siet by activeNetwork.getType() malo vracat 0, pre WiFi 1
            isWiFiOrMobile = (activeNetwork.getType() == ConnectivityManager.TYPE_MOBILE)
                    || (activeNetwork.getType() == ConnectivityManager.TYPE_WIFI);
        }
        return isWiFiOrMobile;
    }

    public void serializeList(List<?> list) {
        SerializationUtils.serialize((ArrayList) list, Strings.SERIALIZED_LIST, this);
        log(TAG, "list has been serialized");
    }

    public List<?> deserializeList() {
        List<?> deserializedList = new ArrayList<Photo>();
        FileInputStream fis;
        try {
            fis = this.openFileInput(Strings.SERIALIZED_LIST);
            deserializedList = (ArrayList)SerializationUtils.deserialize(fis);
            fis.close();
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
        return deserializedList;
    }

    private boolean servicesConnected() {
        int resultCode = GooglePlayServicesUtil.isGooglePlayServicesAvailable(this);
        if (ConnectionResult.SUCCESS == resultCode) {
            Log.d(TAG, "Location Updates - Google Play services is available.");
            return true;
        } else {
            log(TAG, "Google Play services NOT AVAILABLE");
        }
        return false;
    }

    protected boolean checkPlayServices() {
        int resultCode = GooglePlayServicesUtil.isGooglePlayServicesAvailable(this);
        if (resultCode != ConnectionResult.SUCCESS) {
            if (GooglePlayServicesUtil.isUserRecoverableError(resultCode)) {
                GooglePlayServicesUtil.getErrorDialog(resultCode, this,
                        CONNECTION_FAILURE_RESOLUTION_REQUEST).show();
            } else {
                finish();
            }
            return false;
        }
        return true;
    }

    public boolean isLocationEnabled() {
        try {
            return (mLocationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)
                    || mLocationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER));
        } catch (Exception e) { e.printStackTrace(); }
        return false;
    }

    public boolean isGPSEnabled() {
        return mLocationManager.isProviderEnabled(LocationManager.GPS_PROVIDER);
    }

    @Override
    public void onConnectionFailed(ConnectionResult connectionResult) {
        if (connectionResult.hasResolution()) {
            try {
                connectionResult.startResolutionForResult(this, CONNECTION_FAILURE_RESOLUTION_REQUEST);
            } catch (IntentSender.SendIntentException e) {
                e.printStackTrace();
            }
        } else {
            Toast.makeText(this, "Location retrieving error: " + connectionResult.getErrorCode(), Toast.LENGTH_SHORT).show();
        }
    }

    @Override
    public void onConnected(Bundle bundle) {
        LocationServices.FusedLocationApi.requestLocationUpdates(mGoogleApiClient, mLocationRequest, this);
    }

    @Override
    public void onConnectionSuspended(int i) {

    }

    @Override
    public void onLocationChanged(Location location) {

//        log(TAG, String.valueOf(location.getLatitude()));

        if(isFirstKnownLocation) {
            isFirstKnownLocation = false;
            mFirstTime = System.currentTimeMillis(); // get time of this wrong location
            return;
        }

        mSecondTime = System.currentTimeMillis();
        long diffTime = mSecondTime - mFirstTime;
        long diffTimeInSeconds = diffTime / DateUtils.SECOND_IN_MILLIS;
        mTimeDifference = DateUtils.formatElapsedTime(diffTimeInSeconds);

        mFirstTime = mSecondTime;

        mLocation = location;

//        log(TAG, "refresh interval = " + mTimeDifference);
//        log(TAG, "onLocationChanged");

    }

    public void toastShort(String toast) {
        Toast.makeText(this, toast, Toast.LENGTH_SHORT).show();
    }

    public void toastLong(String toast) {
        Toast.makeText(this, toast, Toast.LENGTH_LONG).show();
    }

    public void log(String tag, String log) {
        Log.d(tag, log);
    }

    @Override
    public void intercept(RequestFacade request) {

    }

    @Override
    public Throwable handleError(RetrofitError cause) {
        Response r = cause.getResponse();
        if (r != null)
            switch (r.getStatus()){
                case 401:
                    Log.d(TAG, "returning unauthorised");
                    return new RuntimeException();
                case 400:
                    Log.d(TAG, "returning invalid credentials");
                    return new RuntimeException();
            }
        return cause;
    }

    /**
     *
     * @param animationIdentifier animation resource ID (for example android.R.anim.fade_in)
     * @return created animation
     */
    protected Animation createAnimation(int animationIdentifier) {
        return AnimationUtils.loadAnimation(this, animationIdentifier);
    }

    public void showGPSAlert(){
        AlertDialog.Builder alertDialog = new AlertDialog.Builder(this);

        alertDialog.setTitle("GPS je vypnuté");
        alertDialog.setMessage("Aktivujte v nastaveniach lokalizačnú službu GPS.");

        //alertDialog.setIcon(R.drawable.location);

        alertDialog.setPositiveButton("Nastavenia", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog,int which) {
                Intent intent = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                startActivity(intent);
            }
        });

        alertDialog.setNegativeButton("Zrušiť", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int which) {
                dialog.cancel();
            }
        });

        alertDialog.show();
    }
}
