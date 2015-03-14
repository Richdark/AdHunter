package sk.fiit.adhunter.activities;

import android.app.ActionBar;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;
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
import sk.fiit.adhunter.models.Owner;
import sk.fiit.adhunter.tasks.UploadPhotoTask;
import sk.fiit.adhunter.utils.Strings;

/**
 * Created by Sani on 10. 12. 2014.
 */
public class AdditionalnfoActivity extends BaseActivity implements View.OnClickListener {
    private static final String TAG = "AdditionalInfoActivity";

    private TextView mTextSelectBillboard, mPlus;
    private EditText mComment;
//    private Spinner mOwner;
    private String mTypeOfBillboard, mOwnerSelected;
    private ImageView mBillboard, mCitylight, mHypercube, mMegaboard, mTrojnozka, mUnknown, mImagePlaceholder;
    private ImageView mLastSelected;
    private CurrentPhoto mCurrentPhoto;
    private FrameLayout mLayoutPlaceholder;
    private Spinner mOwnerSpinner;
    private ArrayAdapter mOwnerAdapter;
    private List mOwnerList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_additional_info);

        getServiceInterface().getOwnersList(ownersResponse);

        final ActionBar actionBar = getActionBar();
        if(actionBar != null) {
            actionBar.setTitle("Typ billboardu");
            actionBar.setDisplayHomeAsUpEnabled(true);
        }

        mComment = (EditText) findViewById(R.id.addinfo_comment);
//        mOwner = (Spinner) findViewById(R.id.Activity_Additional_Info_spinnerOwners);
        mLayoutPlaceholder = (FrameLayout) findViewById(R.id.Activity_Additional_Info_layoutPlaceholder);
        mLayoutPlaceholder.setOnClickListener(this);
        mImagePlaceholder = (ImageView) findViewById(R.id.Activity_Additional_Info_imagePlaceholder);
        mImagePlaceholder.setOnClickListener(this);
        mTextSelectBillboard = (TextView) findViewById(R.id.ActivityAdditional_Info_textSelectBillboard);
        mPlus = (TextView) findViewById(R.id.ActivityAdditional_Info_textPlus);
        mOwnerSpinner = (Spinner) findViewById(R.id.Activity_Additional_Info_spinnerOwners);

        mBillboard = (ImageView) findViewById(R.id.imageView_billboard);
        mCitylight = (ImageView) findViewById(R.id.imageView_citylight);
        mHypercube = (ImageView) findViewById(R.id.imageView_hypercube);
        mMegaboard = (ImageView) findViewById(R.id.imageView_megaboard);
        mTrojnozka = (ImageView) findViewById(R.id.imageView_trojnozka);
        mUnknown = (ImageView) findViewById(R.id.imageView_unknown);

        mCurrentPhoto = CurrentPhoto.getInstance();

        // preventing string null case
        mComment.setText("");
//        mOwner.setText("");
        mTypeOfBillboard = "";
        mOwnerSelected = "";

        findViewById(R.id.addinfo_button_upload).setOnClickListener(this);
//        findViewById(R.id.addinfo_select_button).setOnClickListener(this);

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        mTypeOfBillboard = data.getStringExtra("typeOfBillboard");

        if(Strings.isValid(mTextSelectBillboard.getText().toString())
                && Strings.isValid(mPlus.getText().toString())) {
            mTextSelectBillboard.setText("");
            mPlus.setText("");
        }

        switch (Integer.valueOf(mTypeOfBillboard)) {
            case 1:
                mImagePlaceholder.setBackgroundResource(R.drawable.billboard);
                break;
            case 2:
                mImagePlaceholder.setBackgroundResource(R.drawable.megaboard);
                break;
            case 3:
                mImagePlaceholder.setBackgroundResource(R.drawable.citylight);
                break;
            case 4:
                mImagePlaceholder.setBackgroundResource(R.drawable.hypercube);
                break;
            case 5:
                mImagePlaceholder.setBackgroundResource(R.drawable.trojnozka);
                break;
            case 6:
                mImagePlaceholder.setBackgroundResource(R.drawable.noidea);
                break;
            default:
                break;
        }
    }

    @Override
    protected void onPostResume() {
        super.onPostResume();
    }

    @Override
    public void onClick(View view) {
        int id = view.getId();
        switch (id) {
            case R.id.addinfo_button_upload:
                if(isWifiOrMobileConnected(this)) {
                    mOwnerSelected = mOwnerSpinner.getSelectedItem().toString();
                    // photo uploads; button_upload is being showed ONLY after photo has been taken, so the photo surely exists
                    // for now it's sent together
                    mCurrentPhoto.setComment(
                            mOwnerSelected + " " +
                                    mComment.getText().toString() + " " +
                                    mTypeOfBillboard + " " +
                                    Build.MANUFACTURER + " " + Build.MODEL);
                    mCurrentPhoto.setOwner(mOwnerSelected);
                    mCurrentPhoto.setBillboardType(mTypeOfBillboard);

//                    new UploadPhotoTask(this, new UploadPhotoCompleteListener()).execute(CameraActivity.mCurrentPhoto);
                    getServiceInterface().uploadPhoto(new TypedByteArray("image/jpeg", mCurrentPhoto.getImageByteArray()),
                            new TypedString(String.valueOf(mCurrentPhoto.getLatitude())),
                            new TypedString(String.valueOf(mCurrentPhoto.getLongitude())),
                            new TypedString(mCurrentPhoto.getComment()),
                            new TypedString(mCurrentPhoto.getBillboardType()),
                            uploadResponse);
                } else {
                    mCurrentPhoto.setComment(
                            mOwnerSelected + " " +
                                    mComment.getText().toString() + " " +
                                    mTypeOfBillboard + " " +
                                    Build.MANUFACTURER + " " + Build.MODEL);
                    mCurrentPhoto.setOwner(mOwnerSelected);
                    mCurrentPhoto.setBillboardType(mTypeOfBillboard);

                    //save photo to the ArrayList and notify user about uploading photo next time he connects to the internet
                    CameraActivity.sPhotoList.add(mCurrentPhoto);
                    serializeList(CameraActivity.sPhotoList);
                    toastLong(getString(R.string.not_connected));
                }
                break;
            case R.id.Activity_Additional_Info_imagePlaceholder:
                startActivityForResult(new Intent(this, SelectBillboardActivity.class), 0);
                break;
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

    private class UploadPhotoCompleteListener implements AsyncTaskCompleteListener {
        @Override
        public void onTaskComplete() {
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

    private Callback<Response> uploadResponse = new Callback<Response>() {
        @Override
        public void success(Response response, Response response2) {
            toastShort(Strings.parseHtmlResponse(response, "h1"));
            startActivity(new Intent(AdditionalnfoActivity.this, CameraActivity.class));
            finish();
        }

        @Override
        public void failure(RetrofitError error) {
            log(TAG, "failure = " + error.getMessage());
        }
    };

    private Callback<List<Owner>> ownersResponse = new Callback<List<Owner>>() {
        @Override
        public void success(List<Owner> owners, Response response) {
            mOwnerList = new ArrayList<Owner>(owners);
            List<String> ownerStringList = new ArrayList<String>();

            for(Owner o : owners) {
                ownerStringList.add(o.name);
            }

            mOwnerAdapter = new ArrayAdapter<String>(AdditionalnfoActivity.this, android.R.layout.simple_spinner_dropdown_item, ownerStringList);
            if(mOwnerList != null) {
                mOwnerSpinner.setAdapter(mOwnerAdapter);
            }
        }

        @Override
        public void failure(RetrofitError error) {
            log(TAG, "failure = " + error.getMessage());
            mOwnerSpinner.setVisibility(View.GONE);
        }
    };

    @Override
    public void onBackPressed() {
        startActivity(new Intent(AdditionalnfoActivity.this, CameraActivity.class));
        finish();
    }
}
