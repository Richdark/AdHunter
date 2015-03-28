package sk.fiit.adhunter.fragments;

import android.os.Bundle;
import android.preference.PreferenceFragment;

import sk.fiit.adhunter.R;

/**
 * Created by jerry on 28/03/15.
 */
public class SettingsFragment extends PreferenceFragment {

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        addPreferencesFromResource(R.xml.preferences);
    }
}
