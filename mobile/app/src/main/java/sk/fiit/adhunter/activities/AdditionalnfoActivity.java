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

import sk.fiit.adhunter.AsyncTaskCompleteListener;
import sk.fiit.adhunter.R;
import sk.fiit.adhunter.abs.BaseActivity;
import sk.fiit.adhunter.tasks.UploadPhotoTask;

/**
 * Created by Sani on 10. 12. 2014.
 */
public class AdditionalnfoActivity extends BaseActivity implements View.OnClickListener {
    private static final String TAG = "AdditionalInfoActivity";

    private TextView mComment;
    private TextView mOwner;
    private String mTypeOfBillboard;
    private ImageView mBillboard, mCitylight, mHypercube, mMegaboard, mTrojnozka, mUnknown;
    private ImageView mLastSelected;

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

        // preventing string null case
        mComment.setText("");
        mOwner.setText("");
        mTypeOfBillboard = "";

        findViewById(R.id.addinfo_button_upload).setOnClickListener(this);
//        findViewById(R.id.addinfo_button_repeat).setOnClickListener(this);
//        findViewById(R.id.addinfo_button_minus).setOnClickListener(this);
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
                    //photo uploads; button_upload is being showed ONLY after photo has been taken, so the photo surely exists
                    log(TAG, "uploading photo(s)...");
                    // for now it's sent together
                    CameraActivity.mCurrentPhoto.setComment(
                            mOwner.getText().toString() + " " +
                                    mComment.getText().toString() + " " +
                                    mTypeOfBillboard + " " +
                                    Build.MANUFACTURER + " " + Build.MODEL);
                    CameraActivity.mCurrentPhoto.setOwner(mOwner.getText().toString());
                    CameraActivity.mCurrentPhoto.setBillboardType(mTypeOfBillboard);

                    new UploadPhotoTask(this, new UploadPhotoCompleteListener()).execute(CameraActivity.mCurrentPhoto);
                } else {
                    CameraActivity.mCurrentPhoto.setComment(
                            mOwner.getText().toString() +
                                    mComment.getText().toString() +
                                    mTypeOfBillboard +
                                    Build.MANUFACTURER + " " + Build.MODEL);
                    //save photo to the ArrayList and notify user about uploading photo next time he connects to the internet
                    CameraActivity.sPhotoList.add(CameraActivity.mCurrentPhoto);
                    serializeList(CameraActivity.sPhotoList);
                    toastLong("Momentálne nie ste pripojený. Vaša fotka sa uložila a odoslať ju budete môcť pri najbližšom pripojení na internet.");
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

//            case R.id.addinfo_button_repeat:
//                onBackPressed();
//                break;
//            case R.id.addinfo_button_minus:
//                onBackPressed();
//                break;
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
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
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
}
