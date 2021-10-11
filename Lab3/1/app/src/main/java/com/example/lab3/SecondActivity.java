package com.example.lab3;

import static com.example.lab3.FirstActivity.InsertInto;

import androidx.appcompat.app.AppCompatActivity;

import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.TableLayout;

public class SecondActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_second);
    }

    public void InsertIntoDB(View view)
    {
        OurDB dbHelper = new OurDB(this);
        SQLiteDatabase db = dbHelper.getWritableDatabase();
        EditText edit1 = (EditText) findViewById(R.id.editTextTextPersonName);
        EditText edit2 = (EditText) findViewById(R.id.editTextTextPersonName2);
        EditText edit3 = (EditText) findViewById(R.id.editTextTextPersonName3);
        EditText edit4 = (EditText) findViewById(R.id.editTextDate2);

        InsertInto(edit1.getText().toString(),edit2.getText().toString(), edit3.getText().toString(), edit4.getText().toString(),db);
    }
}