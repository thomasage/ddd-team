<?php

declare(strict_types=1);

namespace Team\Infrastructure;

final class DisplayTeamCliView
{
    public function render(DisplayTeamCliViewModel $viewModel): int
    {
        if (!$viewModel->found) {
            printf("\nTeam not found!\n\n");
            foreach ($viewModel->errors as $error) {
                printf("- %s\n", $error);
            }
            echo "\n";

            return 1;
        }

        printf("\nTeam found: %s\n\n", $viewModel->teamName);
        foreach ($viewModel->members as $member) {
            printf("- %s\n", $member);
        }
        echo "\n";

        return 0;
    }
}
