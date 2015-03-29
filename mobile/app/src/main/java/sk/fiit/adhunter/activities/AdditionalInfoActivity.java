package sk.fiit.adhunter.activities;

import android.app.ActionBar;
import android.content.Intent;
import android.os.Bundle;
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

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import retrofit.mime.TypedByteArray;
import retrofit.mime.TypedString;
import sk.fiit.adhunter.R;
import sk.fiit.adhunter.abs.BaseActivity;
import sk.fiit.adhunter.models.CurrentPhoto;
import sk.fiit.adhunter.models.Owner;
import sk.fiit.adhunter.utils.Strings;

/**
 * Created by Sani on 10. 12. 2014.
 */
public class AdditionalInfoActivity extends BaseActivity implements View.OnClickListener {
    private static final String TAG = "AdditionalInfoActivity";

    private TextView mTextSelectBillboard, mPlus;
    private EditText mComment;
    private String mTypeOfBillboard, mOwnerSelected;
    private ImageView mImagePlaceholder;
    private CurrentPhoto mCurrentPhoto;
    private FrameLayout mLayoutPlaceholder;
    private Spinner mOwnerSpinner;
    private ArrayAdapter mOwnerAdapter;
    private List<Owner> mOwnerList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_additional_info);

        final ActionBar actionBar = getActionBar();
        if(actionBar != null) {
            actionBar.setTitle("Pridať informácie");
            actionBar.setDisplayHomeAsUpEnabled(true);
        }

        mComment = (EditText) findViewById(R.id.addinfo_comment);
        mLayoutPlaceholder = (FrameLayout) findViewById(R.id.Activity_Additional_Info_layoutPlaceholder);
        mLayoutPlaceholder.setOnClickListener(this);
        mImagePlaceholder = (ImageView) findViewById(R.id.Activity_Additional_Info_imagePlaceholder);
        mImagePlaceholder.setOnClickListener(this);
        mTextSelectBillboard = (TextView) findViewById(R.id.ActivityAdditional_Info_textSelectBillboard);
        mPlus = (TextView) findViewById(R.id.ActivityAdditional_Info_textPlus);
        mOwnerSpinner = (Spinner) findViewById(R.id.Activity_Additional_Info_spinnerOwners);

        mCurrentPhoto = CurrentPhoto.getInstance();

        // preventing String null case
        mComment.setText("");
        mTypeOfBillboard = "";
        mOwnerSelected = "";

        findViewById(R.id.addinfo_button_upload).setOnClickListener(this);

        if (isWifiOrMobileConnected(this)) {
            getServiceInterface().getOwnersList(ownersResponse);
        } else {
            createOfflineOwners();
        }

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        mTypeOfBillboard = data.getStringExtra(SelectBillboardActivity.EXTRA_BILLBOARD_TYPE);

        if(mTypeOfBillboard.equals("notChosen")) {
            return;
        }

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
    public void onClick(View view) {
        int id = view.getId();
        switch (id) {
            case R.id.addinfo_button_upload:
                if(isWifiOrMobileConnected(this)) {
//                    mOwnerSelected = mOwnerList.get(mOwnerSpinner.getSelectedItemPosition()).id;
//                    log(TAG, "mOwnerSelected = " + mOwnerSelected);

                    if(mCurrentPhoto != null) {
                        setPhotoAttributes();
                        getServiceInterface().uploadPhoto(new TypedByteArray("image/jpeg", mCurrentPhoto.getImageByteArray()),
                                new TypedString(String.valueOf(mCurrentPhoto.getLatitude())),
                                new TypedString(String.valueOf(mCurrentPhoto.getLongitude())),
                                new TypedString(mCurrentPhoto.getComment()),
                                new TypedString(mCurrentPhoto.getBillboardType()),
                                new TypedString(mCurrentPhoto.getOwner()),
                                uploadResponse);
                    } else {
                        toastShort(getResources().getString(R.string.photo_upload_failed));
                    }

                } else {
                    setPhotoAttributes();

                    //save photo to the ArrayList and notify user about uploading photo next time he connects to the internet
                    CameraActivity.sPhotoList.add(mCurrentPhoto);
                    serializeList(CameraActivity.sPhotoList);
                    toastLong(getString(R.string.not_connected));
                    startActivity(new Intent(AdditionalInfoActivity.this, CameraActivity.class));
                    finish();

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
                startActivity(new Intent(AdditionalInfoActivity.this, CameraActivity.class));
                finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    private Callback<Response> uploadResponse = new Callback<Response>() {
        @Override
        public void success(Response response, Response response2) {
            toastShort(Strings.parseHtmlResponse(response, "h1"));
            startActivity(new Intent(AdditionalInfoActivity.this, CameraActivity.class));
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
            initOwnersSpinner();
        }

        @Override
        public void failure(RetrofitError error) {
            log(TAG, "failure = " + error.getMessage());
        }
    };

    public void setPhotoAttributes() {
        mCurrentPhoto.setComment(mComment.getText().toString());

        mOwnerSelected = mOwnerList.get(mOwnerSpinner.getSelectedItemPosition()).id;
        mCurrentPhoto.setOwner(mOwnerSelected);

        mCurrentPhoto.setBillboardType(mTypeOfBillboard);
    }

    private void createOfflineOwners() {
        mOwnerList = new ArrayList<Owner>();
        mOwnerList.add(new Owner("4", "Akzent Media"));
        mOwnerList.add(new Owner("2", "Arton"));
        mOwnerList.add(new Owner("7", "Bigboard"));
        mOwnerList.add(new Owner("8", "Bigmedia"));
        mOwnerList.add(new Owner("1", "euroAWK"));
        mOwnerList.add(new Owner("5", "ISPA"));
        mOwnerList.add(new Owner("6", "Nubium"));
        mOwnerList.add(new Owner("9", "Present"));
        mOwnerList.add(new Owner("10", "Recar"));
        mOwnerList.add(new Owner("3", "XLMedia"));

        initOwnersSpinner();

    }

    private void initOwnersSpinner() {
        mOwnerSpinner.setVisibility(View.VISIBLE);
        List<String> ownerStringList = new ArrayList<String>();
        for(Owner o : mOwnerList) {
            ownerStringList.add(o.name);
            log(TAG, o.name);
        }
        mOwnerAdapter = new ArrayAdapter<String>
                (AdditionalInfoActivity.this, android.R.layout.simple_spinner_dropdown_item, ownerStringList);

        if(mOwnerList != null) {
            mOwnerSpinner.setAdapter(mOwnerAdapter);
        }
    }

    @Override
    public void onBackPressed() {
        startActivity(new Intent(AdditionalInfoActivity.this, CameraActivity.class));
        finish();
    }
}
