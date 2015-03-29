package sk.fiit.adhunter.fragments;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.ListPreference;
import android.preference.Preference;
import android.preference.PreferenceFragment;

import sk.fiit.adhunter.R;

/**
 * Created by jerry on 28/03/15.
 */
public class SettingsFragment extends PreferenceFragment implements SharedPreferences.OnSharedPreferenceChangeListener {

    private static final String TAG = SettingsFragment.class.getSimpleName();

    public static final String KEY_PREF_CONNECTION_TYPE = "pref_connectionType";

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        addPreferencesFromResource(R.xml.preferences);

        SharedPreferences sp = getPreferenceScreen().getSharedPreferences();
        sp.registerOnSharedPreferenceChangeListener(this);

        ListPreference lp = (ListPreference) findPreference(KEY_PREF_CONNECTION_TYPE);
        lp.setSummary(lp.getEntry());

    }

    @Override
    public void onSharedPreferenceChanged(SharedPreferences sharedPreferences, String key) {
        Preference connectionPref = findPreference(key);

        if (key.equals(KEY_PREF_CONNECTION_TYPE)) {

            if (connectionPref instanceof ListPreference) { // should be always true
                connectionPref.setSummary(((ListPreference) connectionPref).getEntry());
            }

        }
    }
}
