package com.vcelicky.smog.activities;

import android.app.ActionBar;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import com.vcelicky.smog.AsyncTaskCompleteListener;
import com.vcelicky.smog.R;
import com.vcelicky.smog.abs.BaseActivity;
import com.vcelicky.smog.tasks.UploadPhotoTask;

/**
 * Created by Sani on 10. 12. 2014.
 */
public class AdditionalnfoActivity extends BaseActivity implements View.OnClickListener {
    private static final String TAG = "AdditionalInfoActivity";

    private TextView mComment;
    private TextView mOwner;
    private String mTypeOfBillboard;

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
                            mOwner.getText().toString() +
                                    mComment.getText().toString() +
                                    mTypeOfBillboard +
                                    Build.MODEL);

                    new UploadPhotoTask(this, new UploadPhotoCompleteListener()).execute(CameraActivity.mCurrentPhoto);
                } else {
                    CameraActivity.mCurrentPhoto.setComment(
                            mOwner.getText().toString() +
                                    mComment.getText().toString() +
                                    mTypeOfBillboard +
                                    Build.MODEL);
                    //save photo to the ArrayList and notify user about uploading photo next time he connects to the internet
                    CameraActivity.sPhotoList.add(CameraActivity.mCurrentPhoto);
                    serializeList(CameraActivity.sPhotoList);
                    toastLong("Momentálne nie ste pripojený. Vaša fotka sa uložila a odoslať ju budete môcť pri najbližšom pripojení na internet.");
                }
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
        }
    }
}
