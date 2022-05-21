<?php

namespace App\Observers;

use App\Models\Food;

class FoodObserver
{
	/**
	 * @param  Food $food
	 * @return void
	 */
	public function updating(Food $food)
	{
		if (!$food->isDirty('slug')) {
			return;
		}

		// Rename files to match new slug.
		$keys = ['front_image', 'info_image'];
		foreach ($keys as $key) {
			if ($food->$key) {
				$newFilename = $food->uploadedFilename($key, $food->$key);
				$oldFilename = preg_replace('/\/' . $food->slug . '\./', '/' . $food->getOriginal('slug') . '.', $newFilename);
				$oldPath = public_path($oldFilename);
				$newPath = public_path($newFilename);
				if (file_exists($oldPath) && !file_exists($newPath)) {
					rename($oldPath, $newPath);
					$food->$key = $newFilename;
				}
			}
		}
	}

	/**
	 * @param  Food $food
	 * @return void
	 */
	public function updated(Food $food)
	{
		// When uploading or removing file, delete the old file.
		$keys = ['front_image', 'info_image'];
		foreach ($keys as $key) {
			if ($food->isDirty($key)) {
				$filename = $food->getOriginal($key);
				if ($filename) {
					$path = public_path($filename);
					if (file_exists($path)) {
						unlink($path);
					}
				}
			}
		}
	}

	/**
	 * @param  Food $food
	 * @return void
	 */
	public function deleted(Food $food)
	{
		// Delete associated files.
		$keys = ['front_image', 'info_image'];
		foreach ($keys as $key) {
			if ($food->$key) {
				$path = public_path($food->$key);
				if (file_exists($path)) {
					unlink($path);
				}
			}
		}

		// Rename slug to allow new rows to be created with same slug.
		$food->slug = 'deleted-' . strtotime('now') . '-' . $food->slug;
		$food->front_image = null;
		$food->info_image = null;
		$food->save();
	}
}
