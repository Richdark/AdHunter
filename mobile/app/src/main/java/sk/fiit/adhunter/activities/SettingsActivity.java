package sk.fiit.adhunter.activities;

import android.app.ActionBar;
import android.os.Bundle;
import android.view.MenuItem;

import sk.fiit.adhunter.abs.BaseActivity;
import sk.fiit.adhunter.fragments.SettingsFragment;

/**
 * Created by jerry on 28/03/15.
 */
public class SettingsActivity extends BaseActivity {

    private static final String TAG = SettingsActivity.class.getSimpleName();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        getFragmentManager().beginTransaction()
                .replace(android.R.id.content, new SettingsFragment())
                .commit();

        final ActionBar actionBar = getActionBar();
        if(actionBar != null) {
            actionBar.setTitle("Nastavenia");
            actionBar.setDisplayHomeAsUpEnabled(true);
        }
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                break;
            default:
                return super.onOptionsItemSelected(item);
        }
        return true;
    }
}
