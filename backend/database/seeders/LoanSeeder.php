<?php

namespace Database\Seeders;

use App\Models\Copy;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::with('user')->limit(10)->get();
        $availableCopies = Copy::where('status', 'available')->limit(20)->get();
        $staff = Staff::with('user')->limit(3)->get();

        if ($availableCopies->isEmpty()) {
            $this->command->warn('No available copies to create loans.');
            return;
        }

        // Create active loans (about 40% of copies)
        $activeLoanCount = (int) ($availableCopies->count() * 0.4);
        for ($i = 0; $i < $activeLoanCount; $i++) {
            $copy = $availableCopies->get($i);
            if (!$copy) break;

            $member = $members->random();
            $staffMember = $staff->random();

            $borrowedDate = now()->subDays(rand(1, 20));
            $dueDate = $borrowedDate->copy()->addDays(rand(14, 30));

            Loan::create([
                'copy_id' => $copy->id,
                'member_id' => $member->id,
                'staff_id' => $staffMember->id,
                'borrowed_date' => $borrowedDate,
                'due_date' => $dueDate,
                'return_date' => null,
                'status' => $borrowedDate->copy()->addDays(14)->isPast() ? 'overdue' : 'active',
                'renewed_count' => rand(0, 2),
                'notes' => rand(0, 1) ? 'Member requested to be notified when due' : null,
            ]);

            // Update copy status
            $copy->update(['status' => 'borrowed']);
        }

        // Create returned loans (about 50% of copies)
        $returnedLoanCount = (int) ($availableCopies->count() * 0.5);
        for ($i = $activeLoanCount; $i < $activeLoanCount + $returnedLoanCount; $i++) {
            if (!isset($availableCopies[$i])) break;

            $copy = $availableCopies[$i];
            $member = $members->random();
            $staffMember = $staff->random();

            $borrowedDate = now()->subDays(rand(30, 180));
            $dueDate = $borrowedDate->copy()->addDays(rand(14, 30));
            $returnDate = $borrowedDate->copy()->addDays(rand(10, 35));

            Loan::create([
                'copy_id' => $copy->id,
                'member_id' => $member->id,
                'staff_id' => $staffMember->id,
                'borrowed_date' => $borrowedDate,
                'due_date' => $dueDate,
                'return_date' => $returnDate,
                'status' => 'returned',
                'renewed_count' => rand(0, 2),
                'notes' => rand(0, 3) === 0 ? 'Returned in good condition' : null,
            ]);
        }
    }
}
