package sk.fiit.adhunter.activities;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ImageView;

import sk.fiit.adhunter.R;
import sk.fiit.adhunter.abs.BaseActivity;

/**
 * Created by Šanis on 11. 12. 2014.
 */
public class SelectBillboardActivity extends BaseActivity implements View.OnClickListener {

    private String mTypeOfBillboard;
    private ImageView mLastSelected;
    private ImageView mBillboard, mCitylight, mHypercube, mMegaboard, mTrojnozka, mUnknown;
    EditText mEdit;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_select_billboard);
        initListeners();
    }

    private void initListeners() {


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



//        ImageButton Button1 = (ImageButton) findViewById(R.id.holderButton1);
//        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
//        Button1.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                mType = "billboard";
//                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
//                finish();
////                onBackPressed();
//            }
//        });
//
//        ImageButton Button2 = (ImageButton) findViewById(R.id.holderButton2);
//        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
//        Button2.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                mType = "megaboard";
//                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
////                onBackPressed();
//                finish();
//            }
//        });
//
//        ImageButton Button3 = (ImageButton) findViewById(R.id.holderButton3);
//        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
//        Button3.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                mType = "citylight";
//                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
////                onBackPressed();
//                finish();
//            }
//        });
//
//        ImageButton Button4 = (ImageButton) findViewById(R.id.holderButton4);
//        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
//        Button4.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                mType = "trojnozka";
////                onBackPressed();
//                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
//                finish();
//            }
//        });
//
//        ImageButton Button5 = (ImageButton) findViewById(R.id.holderButton5);
//        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
//        Button5.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                mType = "hypercube";
////                onBackPressed();
//                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
//                finish();
//            }
//        });
//
//        Button Button6 = (Button) findViewById(R.id.holderButtonAccept);
//        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
//        Button6.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                mType = mEdit.getText().toString();
////                onBackPressed();
//                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
//                finish();
//            }
//        });

    }

    public String getTypeOfBillboard() {
        return mTypeOfBillboard;
    }

    @Override
    public void onClick(View view) {
        switch (view.getId()) {
            case R.id.imageView_billboard:
//                mTypeOfBillboard = "billboard";
                mTypeOfBillboard = "1";
//                setBillboardClicked(mBillboard);
                setResult(1, new Intent().putExtra("typeOfBillboard", mTypeOfBillboard));
                finish();
                break;
            case R.id.imageView_megaboard:
//                mTypeOfBillboard = "megaboard";
                mTypeOfBillboard = "2";
//                setBillboardClicked(mMegaboard);
                setResult(1, new Intent().putExtra("typeOfBillboard", mTypeOfBillboard));
                finish();
                break;
            case R.id.imageView_citylight:
//                mTypeOfBillboard = "citylight";
                mTypeOfBillboard = "3";
//                setBillboardClicked(mCitylight);
                setResult(1, new Intent().putExtra("typeOfBillboard", mTypeOfBillboard));
                finish();
                break;
            case R.id.imageView_hypercube:
//                mTypeOfBillboard = "hypercube";
                mTypeOfBillboard = "4";
//                setBillboardClicked(mHypercube);
                setResult(1, new Intent().putExtra("typeOfBillboard", mTypeOfBillboard));
                finish();
                break;
            case R.id.imageView_trojnozka:
//                mTypeOfBillboard = "trojnozka";
                mTypeOfBillboard = "5";
//                setBillboardClicked(mTrojnozka);
                setResult(1, new Intent().putExtra("typeOfBillboard", mTypeOfBillboard));
                finish();
                break;
            case R.id.imageView_unknown:
//                mTypeOfBillboard = "unknown";
                mTypeOfBillboard = "6";
//                setBillboardClicked(mUnknown);
                setResult(1, new Intent().putExtra("typeOfBillboard", mTypeOfBillboard));
                finish();
                break;
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
