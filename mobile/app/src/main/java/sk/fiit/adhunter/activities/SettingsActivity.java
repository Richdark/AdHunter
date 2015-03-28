package sk.fiit.adhunter.activities;

import android.app.Activity;
import android.os.Bundle;

import sk.fiit.adhunter.fragments.SettingsFragment;

/**
 * Created by jerry on 28/03/15.
 */
public class SettingsActivity extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        getFragmentManager().beginTransaction()
                .replace(android.R.id.content, new SettingsFragment())
                .commit();
    }
}
