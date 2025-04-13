import { CoachProfile } from './Coach/Profile';
import { TeamSchedule } from './Coach/TeamSchedule';
import { PlayerList } from './Coach/PlayerList';
import { Statistics } from './Coach/Statistics';
import { Notes } from './Coach/Notes';

export default function CoachDashboard() {
  return (
    <div className="p-6 space-y-8">
      <h1 className="text-3xl font-bold mb-6 border-b pb-2">Coach Dashboard</h1>
      
      <section>
        <CoachProfile />
      </section>

      <section>
        <TeamSchedule />
      </section>

      <section>
        <PlayerList />
      </section>

      <section>
        <Statistics />
      </section>

      <section>
        <Notes />
      </section>
    </div>
  );
}
