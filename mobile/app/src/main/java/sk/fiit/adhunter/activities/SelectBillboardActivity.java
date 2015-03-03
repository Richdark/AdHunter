package sk.fiit.adhunter.activities;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;

import sk.fiit.adhunter.R;
import sk.fiit.adhunter.abs.BaseActivity;

/**
 * Created by Šanis on 11. 12. 2014.
 */
public class SelectBillboardActivity extends BaseActivity {

    private String mType;
    EditText mEdit;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_select_billboard);
        mType = "";

        initListeners();
    }

    private void initListeners() {
        ImageButton Button1 = (ImageButton) findViewById(R.id.holderButton1);
        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
        Button1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mType = "billboard";
                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
                finish();
//                onBackPressed();
            }
        });

        ImageButton Button2 = (ImageButton) findViewById(R.id.holderButton2);
        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
        Button2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mType = "megaboard";
                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
//                onBackPressed();
                finish();
            }
        });

        ImageButton Button3 = (ImageButton) findViewById(R.id.holderButton3);
        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
        Button3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mType = "citylight";
                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
//                onBackPressed();
                finish();
            }
        });

        ImageButton Button4 = (ImageButton) findViewById(R.id.holderButton4);
        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
        Button4.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mType = "trojnozka";
//                onBackPressed();
                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
                finish();
            }
        });

        ImageButton Button5 = (ImageButton) findViewById(R.id.holderButton5);
        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
        Button5.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mType = "hypercube";
//                onBackPressed();
                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
                finish();
            }
        });

        Button Button6 = (Button) findViewById(R.id.holderButtonAccept);
        //final Intent intent_upl = new Intent(this, TakenPhotoPreview.class);
        Button6.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mType = mEdit.getText().toString();
//                onBackPressed();
                setResult(1, new Intent().putExtra("typeOfBillboard", mType));
                finish();
            }
        });

    }

    public String getmType() {
        return mType;
    }
}
