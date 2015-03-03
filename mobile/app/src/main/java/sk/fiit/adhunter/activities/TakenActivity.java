package sk.fiit.adhunter.activities;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.util.Log;
import android.widget.ImageView;

import sk.fiit.adhunter.R;
import sk.fiit.adhunter.abs.BaseActivity;

import java.io.File;

/**
 * Created by jerry on 13. 11. 2014.
 * Class to process taken picture.
 */
public class TakenActivity extends BaseActivity {
    private static final String TAG = "TakenActivity";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Log.d(TAG, "onCreate()");
        setContentView(R.layout.activity_taken);

        String pathOfFile = getIntent().getExtras().getString("path");
        File imgFile = new File(pathOfFile);
        if(imgFile.exists()) {
            Bitmap bitmap = BitmapFactory.decodeFile(pathOfFile);
            BitmapFactory.Options options = new BitmapFactory.Options();
            options.inJustDecodeBounds = false;
            options.inPreferredConfig = Bitmap.Config.RGB_565;
            options.inDither = true;
            ImageView imageView = (ImageView) findViewById(R.id.taken_picture);
            imageView.setImageBitmap(bitmap);
        }
    }
}
