package sk.fiit.adhunter.activities;

import android.app.ActionBar;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import retrofit.mime.TypedByteArray;
import retrofit.mime.TypedString;
import sk.fiit.adhunter.AsyncTaskCompleteListener;
import sk.fiit.adhunter.R;
import sk.fiit.adhunter.abs.BaseActivity;
import sk.fiit.adhunter.models.CurrentPhoto;
import sk.fiit.adhunter.tasks.UploadPhotoTask;
import sk.fiit.adhunter.utils.Strings;

/**
 * Created by Sani on 10. 12. 2014.
 */
public class AdditionalnfoActivity extends BaseActivity implements View.OnClickListener, Callback<Response> {
    private static final String TAG = "AdditionalInfoActivity";

    private TextView mComment;
    private TextView mOwner;
    private String mTypeOfBillboard;
    private ImageView mBillboard, mCitylight, mHypercube, mMegaboard, mTrojnozka, mUnknown;
    private ImageView mLastSelected;
    private CurrentPhoto mCurrentPhoto;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_additional_info);

        final ActionBar actionBar = getActionBar();
        if(actionBar != null) {
            actionBar.setTitle("Typ billboardu");
            actionBar.setDisplayHomeAsUpEnabled(true);
        }

        mComment = (TextView) findViewById(R.id.addinfo_comment);
        mOwner = (TextView) findViewById(R.id.addinfo_owner);

        mBillboard = (ImageView) findViewById(R.id.imageView_billboard);
        mBillboard.setOnClickListener(this);
        mCitylight = (ImageView) findViewById(R.id.imageView_citylight);
        mCitylight.setOnClickListener(this);
        mHypercube = (ImageView) findViewById(R.id.imageView_hypercube);
        mHypercube.setOnClickListener(this);
        mMegaboard = (ImageView) findViewById(R.id.imageView_megaboard);
        mMegaboard.setOnClickListener(this);
        mTrojnozka = (ImageView) findViewById(R.id.imageView_trojnozka);
        mTrojnozka.setOnClickListener(this);
        mUnknown = (ImageView) findViewById(R.id.imageView_unknown);
        mUnknown.setOnClickListener(this);

        mCurrentPhoto = CurrentPhoto.getInstance();

        // preventing string null case
        mComment.setText("");
        mOwner.setText("");
        mTypeOfBillboard = "";

        findViewById(R.id.addinfo_button_upload).setOnClickListener(this);
//        findViewById(R.id.addinfo_select_button).setOnClickListener(this);

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        mTypeOfBillboard = data.getStringExtra("typeOfBillboard");
        log(TAG, "returned String = " + mTypeOfBillboard);
    }

    @Override
    public void onClick(View view) {
        int id = view.getId();
        switch (id) {
            case R.id.addinfo_button_upload:
                if(isWifiOrMobileConnected(this)) {
                    // photo uploads; button_upload is being showed ONLY after photo has been taken, so the photo surely exists
                    // for now it's sent together
                    mCurrentPhoto.setComment(
                            mOwner.getText().toString() + " " +
                                    mComment.getText().toString() + " " +
                                    mTypeOfBillboard + " " +
                                    Build.MANUFACTURER + " " + Build.MODEL);
                    mCurrentPhoto.setOwner(mOwner.getText().toString());
                    mCurrentPhoto.setBillboardType(mTypeOfBillboard);

//                    new UploadPhotoTask(this, new UploadPhotoCompleteListener()).execute(CameraActivity.mCurrentPhoto);
                    getServiceInterface().uploadPhoto(new TypedByteArray("image/jpeg", mCurrentPhoto.getImageByteArray()),
                            new TypedString(String.valueOf(mCurrentPhoto.getLatitude())),
                            new TypedString(String.valueOf(mCurrentPhoto.getLongitude())),
                            new TypedString(mCurrentPhoto.getComment()),
                            new TypedString(mCurrentPhoto.getBillboardType()),
                            this);
                } else {
                    mCurrentPhoto.setComment(
                            mOwner.getText().toString() +
                                    mComment.getText().toString() +
                                    mTypeOfBillboard +
                                    Build.MANUFACTURER + " " + Build.MODEL);
                    //save photo to the ArrayList and notify user about uploading photo next time he connects to the internet
                    CameraActivity.sPhotoList.add(mCurrentPhoto);
                    serializeList(CameraActivity.sPhotoList);
                    toastLong(getString(R.string.not_connected));
                }
                break;
            case R.id.imageView_billboard:
//                mTypeOfBillboard = "billboard";
                mTypeOfBillboard = "1";
                setBillboardClicked(mBillboard);
                break;
            case R.id.imageView_megaboard:
//                mTypeOfBillboard = "megaboard";
                mTypeOfBillboard = "2";
                setBillboardClicked(mMegaboard);
                break;
            case R.id.imageView_citylight:
//                mTypeOfBillboard = "citylight";
                mTypeOfBillboard = "3";
                setBillboardClicked(mCitylight);
                break;
            case R.id.imageView_hypercube:
//                mTypeOfBillboard = "hypercube";
                mTypeOfBillboard = "4";
                setBillboardClicked(mHypercube);
                break;
            case R.id.imageView_trojnozka:
//                mTypeOfBillboard = "trojnozka";
                mTypeOfBillboard = "5";
                setBillboardClicked(mTrojnozka);
                break;
            case R.id.imageView_unknown:
//                mTypeOfBillboard = "unknown";
                mTypeOfBillboard = "6";
                setBillboardClicked(mUnknown);
                break;

//            case R.id.addinfo_select_button:
//                startActivityForResult(new Intent(this, SelectBillboardActivity.class), 0);
            default:
                break;
        }
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                startActivity(new Intent(AdditionalnfoActivity.this, CameraActivity.class));
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    @Override
    public void success(Response response, Response response2) {
        toastShort(Strings.parseHtmlResponse(response, "h1"));
        startActivity(new Intent(AdditionalnfoActivity.this, CameraActivity.class));
        finish();
    }

    @Override
    public void failure(RetrofitError error) {
        toastShort("error = " + error.getMessage());
    }

    private class UploadPhotoCompleteListener implements AsyncTaskCompleteListener {
        @Override
        public void onTaskComplete() {
            Log.d(TAG, "onTaskComplete, mehehe");
            startActivity(new Intent(AdditionalnfoActivity.this, CameraActivity.class));
            finish();
        }
    }

    private void setBillboardClicked(ImageView imageView) {
        if(mLastSelected != null) {
            mLastSelected.setAlpha(1.0f);
        }
        imageView.setAlpha(0.5f);
        mLastSelected = imageView;
    }

    @Override
    public void onBackPressed() {
        startActivity(new Intent(AdditionalnfoActivity.this, CameraActivity.class));
        finish();
    }
}
