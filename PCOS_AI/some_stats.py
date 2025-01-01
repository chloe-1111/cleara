#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Mon Jun 26 17:30:48 2023

@author: azimospanov
"""

import pandas as pd
import numpy as np

import matplotlib.pyplot as plt

#%%
path = "C:\xampp\htdocs\PCOScompanion\PCOS_AI\data.csv" # path to csv file
df = pd.read_csv(path, sep=";")


def generate_stats(df, category, num_bins = 10):
    hist = df[category].plot.hist(bins=num_bins, edgecolor='black')
    
    plt.title(f'{category} Distribution')
    plt.xlabel(f'{category}')
    plt.ylabel('Frequency')
    
    plt.show()
    
    counts, bins = np.histogram(df[category], bins=num_bins)
    print(f"{'-' * 20}{category} stats{'-' * 20}")
    print(f"{bins}\n")
    
#%% Generate Stats
for category in ["age", "height", "weight"]:
    generate_stats(df, category, 3)