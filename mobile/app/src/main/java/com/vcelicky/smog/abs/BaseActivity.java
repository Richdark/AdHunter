package com.vcelicky.smog.abs;

import android.app.Activity;
import android.content.Context;
import android.content.IntentSender;
import android.location.Location;
import android.location.LocationManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesClient;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.location.LocationClient;
import com.google.android.gms.location.LocationListener;
import com.google.android.gms.location.LocationRequest;
import com.vcelicky.smog.R;
import com.vcelicky.smog.models.Photo;
import com.vcelicky.smog.services.ServiceInterface;
import com.vcelicky.smog.utils.Config;
import com.vcelicky.smog.utils.SerializationUtils;
import com.vcelicky.smog.utils.Strings;

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
public class BaseActivity extends Activity implements GooglePlayServicesClient.ConnectionCallbacks,
        GooglePlayServicesClient.OnConnectionFailedListener,
        LocationListener, RequestInterceptor, ErrorHandler {
    private static final String TAG = "BaseActivity";

    private LocationClient mLocationClient;
    private LocationRequest mLocationRequest;
    protected LocationManager mLocationManager;
    protected Location mLocation;
    protected ServiceInterface mServiceInterface;

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
        if (servicesConnected()) {
            mLocationClient = new LocationClient(this, this, this);
            mLocationRequest = LocationRequest.create();
            mLocationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);
            mLocationRequest.setInterval(5000);
            mLocationRequest.setFastestInterval(5000);
        }
    }

    public ServiceInterface getServiceInterface() {
        return mServiceInterface;
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.base, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
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
        mLocation = mLocationClient.getLastLocation();
        mLocationClient.requestLocationUpdates(mLocationRequest, this);
    }

    @Override
    public void onDisconnected() {
    }

    @Override
    public void onLocationChanged(Location location) {
        this.mLocation = location;
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
}
